<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Department ID is required.", BASE_URL . "/departments");
}

$department = new Department();
$row = $department->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Department not found.", BASE_URL . "/departments");
}

admin_page_start("Edit Department", "departments");
?>

<form method="post" action="<?= e(BASE_URL . "/departments/edit") ?>">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Edit Department</h3>
        </div>
        <div class="form-input">
            <label for="name">Name<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="Name of the department" id="name" name="name" value="<?= e(
                    $row["department_name"],
                ) ?>" required>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <div class="input-box3">
        <button type="submit" class="btn-submit">Update Department</button>
        <a href="<?= e(BASE_URL . "/departments") ?>" class="back">Cancel</a>
    </div>
</form>

<?php admin_page_end(); ?>
