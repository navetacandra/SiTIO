<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/User.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$email = trim((string) ($_POST["email"] ?? ""));
$phone = trim((string) ($_POST["phone"] ?? ""));
$address = trim((string) ($_POST["address"] ?? ""));
$birthDate = trim((string) ($_POST["birth_date"] ?? ""));
$birthLocation = trim((string) ($_POST["birth_location"] ?? ""));
$isActive = isset($_POST["is_active"]);
$password = trim((string) ($_POST["password"] ?? ""));
$back = BASE_URL . "/users/edit?id=" . urlencode($id);

if ($id === "") {
    fail_page("User ID is required.", BASE_URL . "/users");
}

$user = new User();
$row = $user->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("User not found.", BASE_URL . "/users");
}

$position = null;
$departmentId = null;
$studyProgramId = null;
$admissionDate = null;

if ($row["role"] === "struktural") {
    $position = trim((string) ($_POST["position"] ?? ""));

    if ($position === "") {
        fail_page("Position is required.", $back);
    }
} elseif ($row["role"] === "dosen") {
    $departmentId = trim((string) ($_POST["department_id"] ?? ""));

    if ($departmentId === "") {
        fail_page("Department is required.", $back);
    }
} elseif ($row["role"] === "mahasiswa") {
    $departmentId = trim((string) ($_POST["department_id"] ?? ""));
    $studyProgramId = trim((string) ($_POST["study_program_id"] ?? ""));
    $admissionDate = trim((string) ($_POST["admission_date"] ?? ""));

    if ($departmentId === "") {
        fail_page("Department is required.", $back);
    }

    if ($studyProgramId === "") {
        fail_page("Study program is required.", $back);
    }

    if ($admissionDate === "") {
        fail_page("Admission date is required.", $back);
    }
}

try {
    $user->update(
        $id,
        $email,
        $phone,
        $address,
        $birthDate,
        $birthLocation,
        $position,
        $departmentId,
        $studyProgramId,
        $admissionDate,
        $isActive,
        $password === "" ? null : $password,
    );
} catch (Throwable $e) {
    fail_page($e->getMessage(), $back);
}

redirect(BASE_URL . "/users");
