<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

$ticketId = trim((string) ($_GET["ticket_id"] ?? ""));
$ticket = new Ticket();
$tickets = $ticket->all();

$backUrl =
    $ticketId === "" ? BASE_URL . "/ticket-media" : ticket_media_url($ticketId);

admin_page_start("Add Ticket Media", "tickets");
?>

<div class="title-container">
    <h2 class="title">Add Ticket Media</h2>
    <p class="subtitle">Attach a file to a ticket</p>
</div>

<form method="post" action="<?= e(
    BASE_URL . "/ticket-media/create",
) ?>" enctype="multipart/form-data">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Media Details</h3>
        </div>

        <div class="form-input">
            <label for="ticket_id">Ticket<small class="required-mark">*</small></label>
            <div class="input-group">
                <select id="ticket_id" name="ticket_id" required>
                    <option value="">Select ticket</option>
                    <?php foreach ($tickets as $row): ?>
                        <option value="<?= e($row["ticket_id"]) ?>" <?= $row[
    "ticket_id"
] === $ticketId
    ? "selected"
    : "" ?>>
                            #<?= e(
                                substr((string) $row["ticket_id"], 0, 8),
                            ) ?> - <?= e($row["mail"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-input">
            <label for="attachment">File<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png" required>
                <label for="attachment" class="label-file">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3v12"></path>
                        <path d="m17 8-5-5-5 5"></path>
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    </svg>
                    <div>
                        <p id="attachment-name">Add File</p>
                        <small class="input-hint">PDF, JPG, or PNG (max. 5MB)</small>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="input-box3">
        <button type="submit" class="btn-submit">Add Media</button>
        <a href="<?= e($backUrl) ?>" class="back">Cancel</a>
    </div>
</form>

<script>
    document
        .getElementById('attachment')
        .addEventListener('change', function () {
            const name = this.files.length > 0 ? this.files[0].name : 'Add File';
            document.getElementById('attachment-name').textContent = name;
        });
</script>

<?php admin_page_end(); ?>
