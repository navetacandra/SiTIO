<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));

if ($id === "") {
    fail_page("FAQ ID is required.", BASE_URL . "/faqs");
}

if ($question === "") {
    fail_page("Question is required.", BASE_URL . "/faqs/edit");
}

if ($answer === "") {
    fail_page("Answer is required.", BASE_URL . "/faqs/edit");
}

$faq = new Faq();

try {
    $faq->update($id, $question, $answer);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/faqs/edit");
}

redirect("/faqs");
