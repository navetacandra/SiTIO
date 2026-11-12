<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Program.php";

$program = new Program();
$rows = $program->all();

page_start("Programs");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Name</th>
            <th>Start</th>
            <th>End</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="6">No programs.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["program_id"]) ?></td>
                <td><?= e($row["program_type"]) ?></td>
                <td><?= e($row["program_name"]) ?></td>
                <td><?= e($row["start_at"]) ?></td>
                <td><?= e($row["end_at"]) ?></td>
                <td>
                    <a href="edit_get.php?id=<?= e(
                        $row["program_id"],
                    ) ?>">Edit</a>
                    <form method="post" action="delete_post.php">
                        <input type="hidden" name="id" value="<?= e(
                            $row["program_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="create_get.php">Create program</a></p>
<?php page_end(); ?>
