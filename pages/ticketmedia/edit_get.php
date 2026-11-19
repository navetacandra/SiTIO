<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

$ticketId = trim((string) ($_GET["ticket_id"] ?? ""));
$path = trim((string) ($_GET["path"] ?? ""));

if ($ticketId === "" || $path === "") {
    fail_page("Ticket ID and path are required.", BASE_URL . "/ticket-media");
}

$ticketMedia = new TicketMedia();
$row = $ticketMedia->find($ticketId, $path);

if ($row === false) {
    http_response_code(404);
    fail_page("Ticket media not found.", ticket_media_url($ticketId));
}

$backUrl = ticket_media_url($ticketId);

admin_page_start("Edit Ticket Media", "tickets");
?>

<div class="title-container">
    <h2 class="title">Edit Ticket Media</h2>
    <p class="subtitle">Ticket #<?= e(substr($ticketId, 0, 8)) ?></p>
</div>

<form method="post" action="<?= e(BASE_URL . "/ticket-media/edit") ?>">
    <input type="hidden" name="ticket_id" value="<?= e($ticketId) ?>">
    <input type="hidden" name="old_path" value="<?= e($path) ?>">

    <div class="card">
        <div class="title-container">
            <h3 class="title">Media Details</h3>
        </div>

        <div class="form-input">
            <label for="type">Type<small class="required-mark">*</small></label>
            <div class="input-group">
                <select id="type" name="type" required>
                    <option value="image" <?= $row["type"] === "image"
                        ? "selected"
                        : "" ?>>Image</option>
                    <option value="pdf" <?= $row["type"] === "pdf"
                        ? "selected"
                        : "" ?>>PDF</option>
                </select>
            </div>
        </div>

        <div class="form-input">
            <label for="path">Path<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="e.g. uploads/tickets/file.pdf" id="path" name="path" value="<?= e(
                    $row["path"],
                ) ?>" required>
            </div>
        </div>
    </div>

    <div class="input-box3">
        <button type="submit" class="btn-submit">Update Media</button>
        <a href="<?= e($backUrl) ?>" class="back">Cancel</a>
    </div>
</form>

<?php admin_page_end(); ?>
