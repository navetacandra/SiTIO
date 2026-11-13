<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

require_post();

$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));

if ($question === "") {
    fail_page("Question is required.", BASE_URL . "/faqs/create");
}

if ($answer === "") {
    fail_page("Answer is required.", BASE_URL . "/faqs/create");
}

$faq = new Faq();

try {
    $faq->create($question, $answer);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/faqs/create");
}

redirect(BASE_URL . "/faqs");
