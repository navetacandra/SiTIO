<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$ticketId = trim((string) ($_POST["ticket_id"] ?? ""));
$attachment = $_FILES["attachment"] ?? null;

$backUrl =
    BASE_URL .
    "/ticket-media/create" .
    ($ticketId === "" ? "" : "?ticket_id=" . urlencode($ticketId));

if ($ticketId === "") {
    fail_page("Ticket is required.", $backUrl);
}

if (!has_ticket_upload($attachment)) {
    fail_page("File is required.", $backUrl);
}

try {
    $upload = save_ticket_upload($attachment, $ticketId);
} catch (Throwable $e) {
    fail_page($e->getMessage(), $backUrl);
}

try {
    (new TicketMedia())->create($ticketId, $upload["type"], $upload["path"]);
} catch (Throwable $e) {
    @unlink($upload["absolute"]);
    fail_page($e->getMessage(), $backUrl);
}

redirect(ticket_media_url($ticketId));
