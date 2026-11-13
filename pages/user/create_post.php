<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/User.php";

require_post();

$role = trim((string) ($_POST["role"] ?? ""));
$email = trim((string) ($_POST["email"] ?? ""));
$phone = trim((string) ($_POST["phone"] ?? ""));
$password = (string) ($_POST["password"] ?? "");
$address = trim((string) ($_POST["address"] ?? ""));
$birthDate = trim((string) ($_POST["birth_date"] ?? ""));
$birthLocation = trim((string) ($_POST["birth_location"] ?? ""));
$back = "/users/create?role=" . urlencode($role);

if (!in_array($role, ["struktural", "dosen", "mahasiswa"], true)) {
    fail_page("Role is invalid.", "/users/create");
}

$user = new User();

try {
    if ($role === "struktural") {
        $position = trim((string) ($_POST["position"] ?? ""));

        if ($position === "") {
            fail_page("Position is required.", $back);
        }

        $user->createStruktural(
            $email,
            $phone,
            $password,
            $position,
            $address,
            $birthDate,
            $birthLocation,
        );
    } elseif ($role === "dosen") {
        $departmentId = trim((string) ($_POST["department_id"] ?? ""));

        if ($departmentId === "") {
            fail_page("Department is required.", $back);
        }

        $user->createDosen(
            $email,
            $phone,
            $password,
            $departmentId,
            $address,
            $birthDate,
            $birthLocation,
        );
    } else {
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

        $user->createMahasiswa(
            $email,
            $phone,
            $password,
            $departmentId,
            $studyProgramId,
            $admissionDate,
            $address,
            $birthDate,
            $birthLocation,
        );
    }
} catch (Throwable $e) {
    fail_page($e->getMessage(), $back);
}

redirect("/users");
