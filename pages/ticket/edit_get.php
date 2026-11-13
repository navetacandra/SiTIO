<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Ticket ID is required.", "/tickets");
}

$ticket = new Ticket();
$row = $ticket->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Ticket not found.", "/tickets");
}

page_start("Edit Ticket");
?>
<form method="post" action="/tickets/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="mail">Mail</label><br>
        <input id="mail" name="mail" type="email" value="<?= e(
            $row["mail"],
        ) ?>" required>
    </p>
    <p>
        <label for="question">Question</label><br>
        <textarea id="question" name="question" rows="6" cols="60" required><?= e(
            $row["question"],
        ) ?></textarea>
    </p>
    <p>
        <label for="answer">Answer</label><br>
        <textarea id="answer" name="answer" rows="6" cols="60"><?= e(
            (string) $row["answer"],
        ) ?></textarea>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="/tickets">Back</a></p>
<?php page_end(); ?>
