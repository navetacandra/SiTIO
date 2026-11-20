<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

$isRequiredChecked = isset($_POST["is_required"]);

page_start("Create Document Type");
?>
<form method="post" action="<?= BASE_URL ?>/document-types/create">
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" value="<?= old(
            "name",
        ) ?>" required>
    </p>
    <p>
        <label for="is_required">
            <input id="is_required" name="is_required" type="checkbox" value="1"<?= $isRequiredChecked
                ? " checked"
                : "" ?>>
            Required
        </label>
    </p>
    <p>
        <label for="max_size">Max size (KB)</label><br>
        <input id="max_size" name="max_size" type="number" min="1" value="<?= old(
            "max_size",
        ) ?>" required>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="<?= BASE_URL ?>/document-types">Back</a></p>
<?php page_end(); ?>
