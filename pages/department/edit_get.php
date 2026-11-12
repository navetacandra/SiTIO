<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Department ID is required.", "dashboard_get.php");
}

$department = new Department();
$row = $department->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Department not found.", "dashboard_get.php");
}

page_start("Edit Department");
?>
<form method="post" action="edit_post.php">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" value="<?= e(
            $row["department_name"],
        ) ?>" required>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="dashboard_get.php">Back</a></p>
<?php page_end(); ?>
