<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

page_start("Edit Department");
?>
<form method="post" action="<?= BASE_URL ?>/departments/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" value="<?= e(
            $row["department_name"],
        ) ?>" required>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="<?= BASE_URL ?>/departments">Back</a></p>
<?php page_end(); ?>
