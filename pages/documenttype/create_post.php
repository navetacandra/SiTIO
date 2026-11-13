<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/DocumentType.php";

require_post();

$name = trim((string) ($_POST["name"] ?? ""));
$isRequired = isset($_POST["is_required"]);
$maxSize = filter_var($_POST["max_size"] ?? null, FILTER_VALIDATE_INT);

if ($name === "") {
    fail_page("Name is required.", BASE_URL . "/document-types/create");
}

if ($maxSize === false || $maxSize < 1) {
    fail_page(
        "Max size must be a positive integer.",
        BASE_URL . "/document-types/create",
    );
}

$documentType = new DocumentType();

try {
    $documentType->create($name, $isRequired, $maxSize);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/document-types/create");
}

redirect(BASE_URL . "/document-types");
