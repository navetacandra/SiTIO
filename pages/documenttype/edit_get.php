<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/DocumentType.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Document type ID is required.", "/document-types");
}

$documentType = new DocumentType();
$row = $documentType->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Document type not found.", "/document-types");
}

page_start("Edit Document Type");
?>
<form method="post" action="/document-types/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" value="<?= e(
            $row["document_type_name"],
        ) ?>" required>
    </p>
    <p>
        <label>Code</label><br>
        <input type="text" value="<?= e(
            $row["document_type_code"],
        ) ?>" readonly>
    </p>
    <p>
        <label for="is_required">
            <input id="is_required" name="is_required" type="checkbox" value="1"<?= $row[
                "is_required"
            ]
                ? " checked"
                : "" ?>>
            Required
        </label>
    </p>
    <p>
        <label for="max_size">Max size (KB)</label><br>
        <input id="max_size" name="max_size" type="number" min="1" value="<?= e(
            $row["max_size"],
        ) ?>" required>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="/document-types">Back</a></p>
<?php page_end(); ?>
