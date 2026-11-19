<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Ticket ID is required.", BASE_URL . "/tickets");
}

$ticket = new Ticket();
$row = $ticket->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Ticket not found.", BASE_URL . "/tickets");
}

$media = new TicketMedia();
$attachments = $media->all($id);
$detailUrl = BASE_URL . "/tickets/details?id=" . urlencode($id);

admin_page_start("Edit Tickets", "tickets");
?>

<div class="title-container">
    <h2 class="title">Edit Tickets</h2>
    <p class="subtitle">Manage tickets for support and enquiries</p>
</div>

<form method="post" action="<?= e(
    BASE_URL . "/tickets/edit",
) ?>" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <input type="hidden" name="answer" value="<?= e(
        (string) $row["answer"],
    ) ?>">
    <input type="hidden" name="return_to" value="edit">

    <div class="card">
        <div class="title-container">
            <h2 class="title">Edit a Ticket</h2>
            <h3 class="subtitle">Update the ticket details or add an attachment.</h3>
        </div>
        <div class="form-input">
            <label for="mail">Email<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="email" placeholder="Your email address" id="mail" name="mail" value="<?= e(
                    $row["mail"],
                ) ?>" required>
            </div>
        </div>
        <div class="form-input">
            <label for="question">Question<small class="required-mark">*</small></label>
            <div class="input-group">
                <textarea placeholder="Your question or issue" id="question" name="question" required><?= e(
                    $row["question"],
                ) ?></textarea>
            </div>
        </div>
        <div class="form-input">
            <label for="attachment">Attachment (Optional)</label>
            <div class="input-group">
                <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png">
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
        <?php if ($attachments !== []): ?>
            <div class="form-input">
                <label>Current Attachments</label>
                <div class="content-body-thin">
                    <?php foreach ($attachments as $attachment): ?>
                        <?php $path = (string) $attachment["path"]; ?>
                        <p>
                            <a href="<?= e(
                                media_url($path),
                            ) ?>" target="_blank" rel="noopener"><?= e(
    basename($path),
) ?></a>
                            <span class="badge <?= $attachment["type"] === "pdf"
                                ? "mandatory"
                                : "verification" ?>"><?= e(
    $attachment["type"],
) ?></span>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="input-box3">
        <button type="submit" class="btn-submit">Update Ticket</button>
        <a href="<?= e($detailUrl) ?>" class="back">Cancel</a>
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
