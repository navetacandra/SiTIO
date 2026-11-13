<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));
$name = trim((string) ($_POST["name"] ?? ""));

if ($id === "") {
    fail_page("Department ID is required.", BASE_URL . "/departments");
}

$department = new Department();

try {
    $department->update($id, $name);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/departments");
}

redirect(BASE_URL . "/departments");
