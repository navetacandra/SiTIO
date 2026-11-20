<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

require_post();

$departmentId = trim((string) ($_POST["department_id"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));

if ($departmentId === "") {
    fail_page("Department is required.", BASE_URL . "/study-programs/create");
}

$studyProgram = new StudyProgram();

try {
    $studyProgram->create($departmentId, $name);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/study-programs/create");
}

redirect("/study-programs");
