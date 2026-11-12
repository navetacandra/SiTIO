<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/User.php";

$user = new User();
$rows = $user->all();

page_start("Users");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="6">No users.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["user_id"]) ?></td>
                <td><?= e($row["email"]) ?></td>
                <td><?= e($row["phone"]) ?></td>
                <td><?= e($row["role"]) ?></td>
                <td><?= $row["is_active"] ? "Yes" : "No" ?></td>
                <td>
                    <a href="edit_get.php?id=<?= e(
                        urlencode($row["user_id"]),
                    ) ?>">Edit</a>
                    <a href="../userlegal/edit_get.php?user_id=<?= e(
                        urlencode($row["user_id"]),
                    ) ?>">Legal</a>
                    <form method="post" action="delete_post.php">
                        <input type="hidden" name="id" value="<?= e(
                            $row["user_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="create_get.php">Create user</a></p>

<form method="post" action="logout_post.php">
    <button type="submit">Logout</button>
</form>
<?php page_end(); ?>
