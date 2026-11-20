<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/DocumentType.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));

if ($id === "") {
    fail_page("Document type ID is required.", BASE_URL . "/document-types");
}

$documentType = new DocumentType();

try {
    $documentType->delete($id);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/document-types");
}

redirect("/document-types");
