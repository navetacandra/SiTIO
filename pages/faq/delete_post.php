<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));

if ($id === "") {
    fail_page("FAQ ID is required.", "/faqs");
}

$faq = new Faq();

try {
    $faq->delete($id);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "/faqs");
}

redirect("/faqs");
