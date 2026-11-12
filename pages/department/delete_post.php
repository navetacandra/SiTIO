<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));

if ($id === "") {
    fail_page("Department ID is required.", "dashboard_get.php");
}

$department = new Department();

try {
    $department->delete($id);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "dashboard_get.php");
}

redirect("dashboard_get.php");
