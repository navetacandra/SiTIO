<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/UserLegal.php";

$userLegal = new UserLegal();
$rows = $userLegal->all();

page_start("User Legal");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>User ID</th>
            <th>NIK</th>
            <th>NIM</th>
            <th>NIP</th>
            <th>Passport Number</th>
            <th>KTP</th>
            <th>Passport</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="8">No user legal data.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["user_id"]) ?></td>
                <td><?= e((string) $row["nik"]) ?></td>
                <td><?= e((string) $row["nim"]) ?></td>
                <td><?= e((string) $row["nip"]) ?></td>
                <td><?= e((string) $row["paspor_num"]) ?></td>
                <td><?= e((string) $row["ktp"]) ?></td>
                <td><?= e((string) $row["paspor"]) ?></td>
                <td>
                    <a href="edit_get.php?user_id=<?= e(
                        urlencode($row["user_id"]),
                    ) ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php page_end(); ?>
