<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

$ticketId = trim((string) ($_GET["ticket_id"] ?? ""));
$path = trim((string) ($_GET["path"] ?? ""));

if ($ticketId === "" || $path === "") {
    fail_page("Ticket ID and path are required.", "dashboard_get.php");
}

$ticketMedia = new TicketMedia();
$row = $ticketMedia->find($ticketId, $path);

if ($row === false) {
    http_response_code(404);
    fail_page("Ticket media not found.", "dashboard_get.php");
}

page_start("Edit Ticket Media");
?>
<form method="post" action="edit_post.php">
    <input type="hidden" name="ticket_id" value="<?= e($ticketId) ?>">
    <input type="hidden" name="old_path" value="<?= e($path) ?>">
    <p>
        <label>Ticket ID</label><br>
        <input type="text" value="<?= e($row["ticket_id"]) ?>" readonly>
    </p>
    <p>
        <label for="type">Type</label><br>
        <select id="type" name="type" required>
            <option value="image"<?= $row["type"] === "image"
                ? " selected"
                : "" ?>>Image</option>
            <option value="pdf"<?= $row["type"] === "pdf"
                ? " selected"
                : "" ?>>PDF</option>
        </select>
    </p>
    <p>
        <label for="path">Path</label><br>
        <input id="path" name="path" type="text" value="<?= e(
            $row["path"],
        ) ?>" required>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="dashboard_get.php">Back</a></p>
<?php page_end(); ?>
