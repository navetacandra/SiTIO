<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

require_post();

$name = trim((string) ($_POST["name"] ?? ""));

$department = new Department();

try {
    $department->create($name);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/departments");
}

redirect("/departments");
