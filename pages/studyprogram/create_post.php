<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

require_post();

$departmentId = trim((string) ($_POST["department_id"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));

if ($departmentId === "") {
    fail_page("Department is required.", "create_get.php");
}

if ($name === "") {
    fail_page("Name is required.", "create_get.php");
}

$studyProgram = new StudyProgram();

try {
    $studyProgram->create($departmentId, $name);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "create_get.php");
}

redirect("dashboard_get.php");
