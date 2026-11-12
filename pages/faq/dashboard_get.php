<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

$faq = new Faq();
$rows = $faq->all();

page_start("FAQs");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="4">No FAQs.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["faq_id"]) ?></td>
                <td><?= e($row["question"]) ?></td>
                <td><?= e($row["answer"]) ?></td>
                <td>
                    <a href="edit_get.php?id=<?= e($row["faq_id"]) ?>">Edit</a>
                    <form method="post" action="delete_post.php">
                        <input type="hidden" name="id" value="<?= e(
                            $row["faq_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="create_get.php">Create FAQ</a></p>
<?php page_end(); ?>
