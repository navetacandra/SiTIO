<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

$role = trim((string) ($_GET["role"] ?? ""));
$roles = ["struktural", "dosen", "mahasiswa"];

if (!in_array($role, $roles, true)) {
    page_start("Create User");
    echo "<p>Choose a role:</p>\n";
    echo "<ul>\n";

    foreach ($roles as $option) {
        echo '<li><a href="' .
            BASE_URL .
            "/users/create?role=" .
            e(urlencode($option)) .
            '">' .
            e(ucfirst($option)) .
            "</a></li>\n";
    }

    echo "</ul>\n";
    echo '<p><a href="' . BASE_URL . '/users">Back</a></p>' . "\n";
    page_end();
    exit();
}

$departments = [];
$studyPrograms = [];

if ($role === "dosen" || $role === "mahasiswa") {
    $department = new Department();
    $departments = $department->all();
}

if ($role === "mahasiswa") {
    $studyProgram = new StudyProgram();
    $studyPrograms = $studyProgram->all();
}

$selectedDepartment = (string) ($_POST["department_id"] ?? "");
$selectedStudyProgram = (string) ($_POST["study_program_id"] ?? "");

page_start("Create " . ucfirst($role) . " User");
?>
<form method="post" action="<?= BASE_URL ?>/users/create">
    <input type="hidden" name="role" value="<?= e($role) ?>">
    <p>
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" value="<?= old(
            "email",
        ) ?>" required>
    </p>
    <p>
        <label for="phone">Phone</label><br>
        <input id="phone" name="phone" type="text" value="<?= old(
            "phone",
        ) ?>" required>
    </p>
    <p>
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required>
    </p>
    <p>
        <label for="address">Address</label><br>
        <textarea id="address" name="address" rows="4" cols="60" required><?= old(
            "address",
        ) ?></textarea>
    </p>
    <p>
        <label for="birth_date">Birth date</label><br>
        <input id="birth_date" name="birth_date" type="date" value="<?= old(
            "birth_date",
        ) ?>" required>
    </p>
    <p>
        <label for="birth_location">Birth location</label><br>
        <input id="birth_location" name="birth_location" type="text" value="<?= old(
            "birth_location",
        ) ?>" required>
    </p>

    <?php if ($role === "struktural"): ?>
        <p>
            <label for="position">Position</label><br>
            <input id="position" name="position" type="text" value="<?= old(
                "position",
            ) ?>" required>
        </p>
    <?php endif; ?>

    <?php if ($role === "dosen" || $role === "mahasiswa"): ?>
        <p>
            <label for="department_id">Department</label><br>
            <select id="department_id" name="department_id" required>
                <option value="">Select department</option>
                <?php foreach ($departments as $row): ?>
                    <option value="<?= e($row["department_id"]) ?>"<?= $row[
    "department_id"
] === $selectedDepartment
    ? " selected"
    : "" ?>><?= e($row["department_name"]) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php endif; ?>

    <?php if ($role === "mahasiswa"): ?>
        <p>
            <label for="study_program_id">Study program</label><br>
            <select id="study_program_id" name="study_program_id" required>
                <option value="">Select study program</option>
                <?php foreach ($studyPrograms as $programRow): ?>
                    <option value="<?= e(
                        $programRow["study_program_id"],
                    ) ?>"<?= $programRow["study_program_id"] ===
$selectedStudyProgram
    ? " selected"
    : "" ?>><?= e(
    $programRow["department_name"] . " - " . $programRow["study_program_name"],
) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="admission_date">Admission date</label><br>
            <input id="admission_date" name="admission_date" type="date" value="<?= old(
                "admission_date",
            ) ?>" required>
        </p>
    <?php endif; ?>

    <button type="submit">Save</button>
</form>

<p><a href="<?= BASE_URL ?>/users">Back</a></p>
<?php page_end(); ?>
