<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/StudyProgram.php";

$studyProgram = new StudyProgram();
$rows = $studyProgram->all();

page_start("Study Programs");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Department</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="4">No study programs.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["study_program_id"]) ?></td>
                <td><?= e($row["department_name"]) ?></td>
                <td><?= e($row["study_program_name"]) ?></td>
                <td>
                    <a href="edit_get.php?id=<?= e(
                        $row["study_program_id"],
                    ) ?>">Edit</a>
                    <form method="post" action="delete_post.php">
                        <input type="hidden" name="id" value="<?= e(
                            $row["study_program_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="create_get.php">Create study program</a></p>
<?php page_end(); ?>
