<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

$title = "Create Tickets";
$active = "tickets";
require __DIR__ . "/../_layouts/header.php";
?>

<div class="title-container">
    <h2 class="title">Create Tickets</h2>
    <p class="subtitle">Manage tickets for support and enquiries</p>
</div>

<form method="post" action="<?= e(
    BASE_URL . "/tickets/create",
) ?>" enctype="multipart/form-data">
    <div class="card">
        <div class="title-container">
            <h2 class="title">Submit a Ticket</h2>
            <h3 class="subtitle">No account needed. We will reply to the email you provide.</h3>
        </div>
        <div class="form-input">
            <label for="mail">Email<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="email" placeholder="Your email address" id="mail" name="mail" value="<?= old(
                    "mail",
                ) ?>" required>
            </div>
        </div>
        <div class="form-input">
            <label for="question">Question<small class="required-mark">*</small></label>
            <div class="input-group">
                <textarea placeholder="Your question or issue" id="question" name="question" required><?= old(
                    "question",
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
    </div>
    <div class="input-box3">
        <button type="submit" class="btn-submit">Create Ticket</button>
        <a href="<?= e(BASE_URL . "/tickets") ?>" class="back">Cancel</a>
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

<?php require __DIR__ . "/../_layouts/footer.php"; ?>
