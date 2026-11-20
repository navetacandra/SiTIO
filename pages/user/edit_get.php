<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";
require_once __DIR__ . "/../../models/StudyProgram.php";
require_once __DIR__ . "/../../models/User.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("User ID is required.", BASE_URL . "/users");
}

$user = new User();
$row = $user->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("User not found.", BASE_URL . "/users");
}

$departments = [];
$studyPrograms = [];

if ($row["role"] === "dosen" || $row["role"] === "mahasiswa") {
    $department = new Department();
    $departments = $department->all();
}

if ($row["role"] === "mahasiswa") {
    $studyProgram = new StudyProgram();
    $studyPrograms = $studyProgram->all();
}

$selectedDepartment = (string) ($_POST["department_id"] ?? $row["department_id"]);
$selectedStudyProgram = (string) ($_POST["study_program_id"] ?? $row["study_program_id"]);
$isActiveChecked = ($_SERVER["REQUEST_METHOD"] ?? "") === "POST"
    ? isset($_POST["is_active"])
    : (bool) $row["is_active"];

page_start("Edit User");
?>
<form method="post" action="<?= BASE_URL ?>/users/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label>Role</label><br>
        <input type="text" value="<?= e($row["role"]) ?>" readonly>
    </p>
    <p>
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" value="<?= old(
            "email",
            $row["email"],
        ) ?>" required>
    </p>
    <p>
        <label for="phone">Phone</label><br>
        <input id="phone" name="phone" type="text" value="<?= old(
            "phone",
            $row["phone"],
        ) ?>" required>
    </p>
    <p>
        <label for="address">Address</label><br>
        <textarea id="address" name="address" rows="4" cols="60" required><?= old(
            "address",
            $row["address"],
        ) ?></textarea>
    </p>
    <p>
        <label for="birth_date">Birth date</label><br>
        <input id="birth_date" name="birth_date" type="date" value="<?= old(
            "birth_date",
            $row["birth_date"],
        ) ?>" required>
    </p>
    <p>
        <label for="birth_location">Birth location</label><br>
        <input id="birth_location" name="birth_location" type="text" value="<?= old(
            "birth_location",
            $row["birth_location"],
        ) ?>" required>
    </p>

    <?php if ($row["role"] === "struktural"): ?>
        <p>
            <label for="position">Position</label><br>
            <input id="position" name="position" type="text" value="<?= old(
                "position",
                $row["position"],
            ) ?>" required>
        </p>
    <?php endif; ?>

    <?php if ($row["role"] === "dosen" || $row["role"] === "mahasiswa"): ?>
        <p>
            <label for="department_id">Department</label><br>
            <select id="department_id" name="department_id" required>
                <?php foreach ($departments as $departmentRow): ?>
                    <option value="<?= e(
                        $departmentRow["department_id"],
                    ) ?>"<?= $departmentRow["department_id"] ===
$selectedDepartment
    ? " selected"
    : "" ?>><?= e($departmentRow["department_name"]) ?></option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php endif; ?>

    <?php if ($row["role"] === "mahasiswa"): ?>
        <p>
            <label for="study_program_id">Study program</label><br>
            <select id="study_program_id" name="study_program_id" required>
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
                $row["admission_date"],
            ) ?>" required>
        </p>
    <?php endif; ?>

    <p>
        <label for="is_active">
            <input id="is_active" name="is_active" type="checkbox" value="1"<?= $isActiveChecked
                ? " checked"
                : "" ?>>
            Active
        </label>
    </p>
    <p>
        <label for="password">New password</label><br>
        <input id="password" name="password" type="password">
        <small>Leave empty to keep the current password.</small>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="<?= BASE_URL ?>/users">Back</a></p>
<?php page_end(); ?>
