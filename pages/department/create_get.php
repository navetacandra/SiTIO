<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

$title = "Create Department";
$active = "departments";
require __DIR__ . "/../_layouts/header.php";
?>

<form method="post" action="<?= e(BASE_URL . "/departments/create") ?>">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Create Department</h3>
        </div>
        <div class="form-input">
            <label for="name">Name<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="Name of the department" id="name" name="name" value="<?= old("name") ?>" required>
            </div>
        </div>
    </div>
    <div class="input-box3">
        <button type="submit" class="btn-submit">Create Department</button>
        <a href="<?= e(BASE_URL . "/departments") ?>" class="back">Cancel</a>
    </div>
</form>

<?php require __DIR__ . "/../_layouts/footer.php"; ?>
