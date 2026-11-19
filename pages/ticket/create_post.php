<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$mail = trim((string) ($_POST["mail"] ?? ""));
$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));
$attachment = $_FILES["attachment"] ?? null;

$backUrl = BASE_URL . "/tickets/create";

if ($mail === "") {
    fail_page("Mail is required.", $backUrl);
}

if ($question === "") {
    fail_page("Question is required.", $backUrl);
}

$ticket = new Ticket();

try {
    $ticketId = $ticket->create(
        $mail,
        $question,
        $answer === "" ? null : $answer,
    );
} catch (Throwable $e) {
    fail_page($e->getMessage(), $backUrl);
}

$detailUrl = BASE_URL . "/tickets/details?id=" . urlencode($ticketId);

if (has_ticket_upload($attachment)) {
    try {
        $upload = save_ticket_upload($attachment, $ticketId);
    } catch (Throwable $e) {
        $ticket->delete($ticketId);
        fail_page($e->getMessage(), $backUrl);
    }

    try {
        (new TicketMedia())->create(
            $ticketId,
            $upload["type"],
            $upload["path"],
        );
    } catch (Throwable $e) {
        @unlink($upload["absolute"]);
        $ticket->delete($ticketId);
        fail_page($e->getMessage(), $backUrl);
    }
}

redirect($detailUrl);
