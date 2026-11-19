<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";

admin_page_start("Create Tickets", "tickets");
?>

<div class="title-container">
    <h2 class="title">Create Tickets</h2>
    <p class="subtitle">Manage tickets for support and enquiries</p>
</div>

<form method="post" action="<?= e(BASE_URL . "/tickets/create") ?>">
    <div class="card">
        <div class="title-container">
            <h2 class="title">Submit a Ticket</h2>
            <h3 class="subtitle">No account needed. We will reply to the email you provide.</h3>
        </div>
        <div class="form-input">
            <label for="mail">Email<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="email" placeholder="Your email address" id="mail" name="mail" required>
            </div>
        </div>
        <div class="form-input">
            <label for="question">Question<small class="required-mark">*</small></label>
            <div class="input-group">
                <textarea placeholder="Your question or issue" id="question" name="question" required></textarea>
            </div>
        </div>
    </div>
    <div class="input-box3">
        <button type="submit" class="btn-submit">Create Ticket</button>
        <a href="<?= e(BASE_URL . "/tickets") ?>" class="back">Cancel</a>
    </div>
</form>

<?php admin_page_end(); ?>
