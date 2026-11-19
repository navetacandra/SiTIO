<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$mail = trim((string) ($_POST["mail"] ?? ""));
$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));
$returnTo = (string) ($_POST["return_to"] ?? "edit");
$attachment = $_FILES["attachment"] ?? null;

if ($id === "") {
    fail_page("Ticket ID is required.", BASE_URL . "/tickets");
}

$detailUrl = BASE_URL . "/tickets/details?id=" . urlencode($id);
$editUrl = BASE_URL . "/tickets/edit?id=" . urlencode($id);
$failUrl = $returnTo === "details" ? $detailUrl : $editUrl;

if ($mail === "") {
    fail_page("Mail is required.", $failUrl);
}

if ($question === "") {
    fail_page("Question is required.", $failUrl);
}

$ticket = new Ticket();

try {
    $ticket->update($id, $mail, $question, $answer === "" ? null : $answer);
} catch (Throwable $e) {
    fail_page($e->getMessage(), $failUrl);
}

if (has_ticket_upload($attachment)) {
    try {
        $upload = save_ticket_upload($attachment, $id);
    } catch (Throwable $e) {
        fail_page($e->getMessage(), $failUrl);
    }

    try {
        (new TicketMedia())->create($id, $upload["type"], $upload["path"]);
    } catch (Throwable $e) {
        @unlink($upload["absolute"]);
        fail_page($e->getMessage(), $failUrl);
    }
}

redirect($detailUrl);
