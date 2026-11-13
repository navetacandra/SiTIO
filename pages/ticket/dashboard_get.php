<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

$ticket = new Ticket();
$rows = $ticket->all();

page_start("Tickets");
?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mail</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($rows === []): ?>
            <tr>
                <td colspan="5">No tickets.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e($row["ticket_id"]) ?></td>
                <td><?= e($row["mail"]) ?></td>
                <td><?= e($row["question"]) ?></td>
                <td><?= e((string) $row["answer"]) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/tickets/edit?id=<?= e(
    $row["ticket_id"],
) ?>">Edit</a>
                    <a href="<?= BASE_URL ?>/ticket-media?ticket_id=<?= e(
    $row["ticket_id"],
) ?>">Media</a>
                    <form method="post" action="<?= BASE_URL ?>/tickets/delete">
                        <input type="hidden" name="id" value="<?= e(
                            $row["ticket_id"],
                        ) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="<?= BASE_URL ?>/tickets/create">Create ticket</a></p>
<p><a href="<?= BASE_URL ?>/tickets">Back to list</a></p>
<?php page_end(); ?>
