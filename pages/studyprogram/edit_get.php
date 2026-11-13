<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Study program ID is required.", "/study-programs");
}

$studyProgram = new StudyProgram();
$row = $studyProgram->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Study program not found.", "/study-programs");
}

$department = new Department();
$departments = $department->all();

page_start("Edit Study Program");
?>
<form method="post" action="/study-programs/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="department_id">Department</label><br>
        <select id="department_id" name="department_id" required>
            <?php foreach ($departments as $departmentRow): ?>
                <option value="<?= e(
                    $departmentRow["department_id"],
                ) ?>"<?= $departmentRow["department_id"] ===
$row["department_id"]
    ? " selected"
    : "" ?>><?= e($departmentRow["department_name"]) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" value="<?= e(
            $row["study_program_name"],
        ) ?>" required>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="/study-programs">Back</a></p>
<?php page_end(); ?>
