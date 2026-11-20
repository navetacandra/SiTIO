<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));
$returnTo = (string) ($_POST["return_to"] ?? "edit");
$attachment = $_FILES["attachment"] ?? null;

if ($id === "") {
    fail_page("Ticket ID is required.", BASE_URL . "/tickets");
}

$detailUrl = "/tickets/details?id=" . urlencode($id);
$ticket = new Ticket();

try {
    $ticket->update($id, $answer === "" ? null : $answer);
} catch (Throwable $e) {
    fail_page($e->getMessage(), $detailUrl);
}

redirect($detailUrl);
