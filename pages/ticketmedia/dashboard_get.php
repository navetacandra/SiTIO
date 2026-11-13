<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

$ticketId = trim((string) ($_GET["ticket_id"] ?? ""));
$ticketMedia = new TicketMedia();
$rows = $ticketMedia->all($ticketId === "" ? null : $ticketId);

page_start("Ticket Media");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Ticket ID</th>
            <th>Type</th>
            <th>Path</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="4">No ticket media.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["ticket_id"]) ?></td>
                <td><?= e($row["type"]) ?></td>
                <td><?= e($row["path"]) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/ticket-media/edit?ticket_id=<?= e(
    urlencode($row["ticket_id"]),
) ?>&path=<?= e(urlencode($row["path"])) ?>">Edit</a>
                    <form method="post" action="<?= BASE_URL ?>/ticket-media/delete">
                        <input type="hidden" name="ticket_id" value="<?= e(
                            $row["ticket_id"],
                        ) ?>">
                        <input type="hidden" name="path" value="<?= e(
                            $row["path"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="<?= BASE_URL ?>/ticket-media/create">Create ticket media</a></p>
<p><a href="<?= BASE_URL ?>/ticket-media?ticket_id=<?= e(
    $ticketId,
) ?>">Back</a></p>
<?php page_end(); ?>
