<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

$department = new Department();
$departments = $department->all();

admin_page_start("Create Study Program", "study-programs");
?>

<form method="post" action="<?= e(BASE_URL . "/study-programs/create") ?>">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Create Study Program</h3>
        </div>

        <div class="form-input">
            <label for="department_id">Department<small class="required-mark">*</small></label>
            <div class="input-group">
                <select id="department_id" name="department_id" required>
                    <option value="">Select department</option>
                    <?php foreach ($departments as $row): ?>
                        <option value="<?= e($row["department_id"]) ?>">
                            <?= e($row["department_name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-input">
            <label for="name">Name<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="Name of the study program" id="name" name="name" required>
            </div>
        </div>
    </div>

    <div class="input-box3">
        <button type="submit" class="btn-submit">Create Study Program</button>
        <a href="<?= e(BASE_URL . "/study-programs") ?>" class="back">Cancel</a>
    </div>
</form>

<?php admin_page_end(); ?>