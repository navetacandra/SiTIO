<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$mail = trim((string) ($_POST["mail"] ?? ""));
$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));

if ($id === "") {
    fail_page("Ticket ID is required.", BASE_URL . "/tickets");
}

if ($mail === "") {
    fail_page("Mail is required.", BASE_URL . "/tickets/edit");
}

if ($question === "") {
    fail_page("Question is required.", BASE_URL . "/tickets/edit");
}

$ticket = new Ticket();

try {
    $ticket->update($id, $mail, $question, $answer === "" ? null : $answer);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/tickets/edit");
}

redirect(BASE_URL . "/tickets");
