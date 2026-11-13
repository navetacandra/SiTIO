<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

$department = new Department();
$departments = $department->all();

page_start("Departments");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($departments === []): ?>
            <tr>
                <td colspan="3">No departments.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($departments as $row): ?>
            <tr>
                <td><?= e($row["department_id"]) ?></td>
                <td><?= e($row["department_name"]) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/departments/edit?id=<?= e(
    $row["department_id"],
) ?>">Edit</a>
                    <form method="post" action="<?= BASE_URL ?>/departments/delete">
                        <input type="hidden" name="id" value="<?= e(
                            $row["department_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="<?= BASE_URL ?>/departments/create">Create department</a></p>
<p><a href="<?= BASE_URL ?>/departments">Back to list</a></p>
<?php page_end(); ?>
