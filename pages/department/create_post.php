<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

require_post();

$name = trim((string) ($_POST["name"] ?? ""));

if ($name === "") {
    fail_page("Name is required.", "create_get.php");
}

$department = new Department();

try {
    $department->create($name);
} catch (Throwable $e) {
    fail_page($e->getMessage(), "create_get.php");
}

redirect("dashboard_get.php");
