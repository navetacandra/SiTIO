<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$departmentId = trim((string) ($_POST["department_id"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));

if ($id === "") {
    fail_page("Study program ID is required.", "dashboard_get.php");
}

if ($departmentId === "") {
    fail_page("Department is required.", "edit_get.php?id=" . urlencode($id));
}

if ($name === "") {
    fail_page("Name is required.", "edit_get.php?id=" . urlencode($id));
}

$studyProgram = new StudyProgram();

try {
    $studyProgram->update($id, $departmentId, $name);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "edit_get.php?id=" . urlencode($id));
}

redirect("dashboard_get.php");
