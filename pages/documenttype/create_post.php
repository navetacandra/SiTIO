<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/DocumentType.php";

require_post();

$name = trim((string) ($_POST["name"] ?? ""));
$isRequired = isset($_POST["is_required"]);
$maxSize = filter_var($_POST["max_size"] ?? null, FILTER_VALIDATE_INT);

if ($name === "") {
    fail_page("Name is required.", "create_get.php");
}

if ($maxSize === false || $maxSize < 1) {
    fail_page("Max size must be a positive integer.", "create_get.php");
}

$documentType = new DocumentType();

try {
    $documentType->create($name, $isRequired, $maxSize);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "create_get.php");
}

redirect("dashboard_get.php");
