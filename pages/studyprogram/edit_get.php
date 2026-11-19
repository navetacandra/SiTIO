<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Study program ID is required.", BASE_URL . "/study-programs");
}

$studyProgram = new StudyProgram();
$row = $studyProgram->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Study program not found.", BASE_URL . "/study-programs");
}

$department = new Department();
$departments = $department->all();

admin_page_start("Edit Study Program", "study-programs");
?>

<form method="post" action="<?= e(BASE_URL . "/study-programs/edit") ?>">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Edit Study Program</h3>
        </div>

        <div class="form-input">
            <label for="department_id">Department<small class="required-mark">*</small></label>
            <div class="input-group">
                <select id="department_id" name="department_id" required>
                    <?php foreach ($departments as $departmentRow): ?>
                        <option value="<?= e($departmentRow["department_id"]) ?>" <?= $departmentRow["department_id"] === $row["department_id"] ? "selected" : "" ?>>
                            <?= e($departmentRow["department_name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-input">
            <label for="name">Name<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="Name of the study program" id="name" name="name" value="<?= e($row["study_program_name"]) ?>" required>
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="<?= e($id) ?>">

    <div class="input-box3">
        <button type="submit" class="btn-submit">Update Study Program</button>
        <a href="<?= e(BASE_URL . "/study-programs") ?>" class="back">Cancel</a>
    </div>
</form>

<?php admin_page_end(); ?>