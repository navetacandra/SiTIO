<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

page_start("Create Department");
?>
<form method="post" action="<?= BASE_URL ?>/departments/create">
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" required>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="<?= BASE_URL ?>/departments">Back</a></p>
<?php page_end(); ?>
