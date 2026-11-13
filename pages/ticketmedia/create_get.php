<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

$ticket = new Ticket();
$tickets = $ticket->all();

page_start("Create Ticket Media");
?>
<form method="post" action="<?= BASE_URL ?>/ticket-media/create">
    <p>
        <label for="ticket_id">Ticket</label><br>
        <select id="ticket_id" name="ticket_id" required>
            <option value="">Select ticket</option>
            <?php foreach ($tickets as $row): ?>
                <option value="<?= e($row["ticket_id"]) ?>"><?= e(
    $row["mail"] . " - " . $row["question"],
) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="type">Type</label><br>
        <select id="type" name="type" required>
            <option value="image">Image</option>
            <option value="pdf">PDF</option>
        </select>
    </p>
    <p>
        <label for="path">Path</label><br>
        <input id="path" name="path" type="text" required>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="<?= BASE_URL ?>/ticket-media">Back</a></p>
<?php page_end(); ?>
