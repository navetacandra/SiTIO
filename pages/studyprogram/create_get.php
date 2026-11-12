<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

$department = new Department();
$departments = $department->all();

page_start("Create Study Program");
?>
<form method="post" action="create_post.php">
    <p>
        <label for="department_id">Department</label><br>
        <select id="department_id" name="department_id" required>
            <option value="">Select department</option>
            <?php foreach ($departments as $row): ?>
                <option value="<?= e($row["department_id"]) ?>"><?= e(
    $row["department_name"],
) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" required>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="dashboard_get.php">Back</a></p>
<?php page_end(); ?>
