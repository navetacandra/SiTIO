<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/DocumentType.php";

$documentType = new DocumentType();
$rows = $documentType->all();

page_start("Document Types");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Required</th>
            <th>Max size (KB)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="6">No document types.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["document_type_id"]) ?></td>
                <td><?= e($row["document_type_name"]) ?></td>
                <td><?= e($row["document_type_code"]) ?></td>
                <td><?= $row["is_required"] ? "Yes" : "No" ?></td>
                <td><?= e($row["max_size"]) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/document-types/edit?id=<?= e(
    $row["document_type_id"],
) ?>">Edit</a>
                    <form method="post" action="<?= BASE_URL ?>/document-types/delete">
                        <input type="hidden" name="id" value="<?= e(
                            $row["document_type_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="<?= BASE_URL ?>/document-types/create">Create document type</a></p>
<p><a href="<?= BASE_URL ?>/document-types">Back to list</a></p>
<?php page_end(); ?>
