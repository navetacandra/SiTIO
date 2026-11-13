<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/DocumentType.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));
$isRequired = isset($_POST["is_required"]);
$maxSize = filter_var($_POST["max_size"] ?? null, FILTER_VALIDATE_INT);

if ($id === "") {
    fail_page("Document type ID is required.", BASE_URL . "/document-types");
}

if ($name === "") {
    fail_page("Name is required.", BASE_URL . "/document-types/edit");
}

if ($maxSize === false || $maxSize < 1) {
    fail_page(
        "Max size must be a positive integer.",
        BASE_URL . "/document-types/edit",
    );
}

$documentType = new DocumentType();

try {
    $documentType->update($id, $name, $isRequired, $maxSize);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/document-types/edit");
}

redirect(BASE_URL . "/document-types");
