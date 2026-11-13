<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

require_post();

$mail = trim((string) ($_POST["mail"] ?? ""));
$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));

if ($mail === "") {
    fail_page("Mail is required.", "/tickets/create");
}

if ($question === "") {
    fail_page("Question is required.", "/tickets/create");
}

$ticket = new Ticket();

try {
    $ticket->create($mail, $question, $answer === "" ? null : $answer);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "/tickets/create");
}

redirect("/tickets");
