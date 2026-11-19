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
$answered = trim((string) ($row["answer"] ?? "")) !== "";

admin_page_start("Ticket Details", "tickets");
?>

<div class="card4">
    <div class="title-container">
        <h3 class="title details-title">
            <span>Tiket #<?= e(
                substr((string) $row["ticket_id"], 0, 8),
            ) ?></span>
            <span class="badge <?= $answered
                ? "approved"
                : "pending" ?>"><?= $answered ? "Done" : "Pending" ?></span>
        </h3>
        <p class="subtitle2"><?= e($row["mail"]) ?></p>
    </div>

    <div class="card-detail">
        <div style="padding-bottom: 1%">
            <div class="input-cols">
                <p class="content-question-title">QUESTION</p>
            </div>
            <div class="input-cols">
                <p class="content-question-body"><?= e($row["question"]) ?></p>
            </div>
        </div>
    </div>

    <div class="card-detail-answer" style="margin-top: 1.5%;">
        <div style="padding-bottom: 1%">
            <div class="input-cols">
                <p class="content-answer-title">ANSWER</p>
            </div>
            <div class="input-cols">
                <?php if ($answered): ?>
                    <p class="content-answer-body"><?= e($row["answer"]) ?></p>
                <?php else: ?>
                    <p class="content-answer-body">Not answered yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card4" style="margin-top: 2%;">
    <div class="title-container">
        <h3 class="title details-title">
            <span>Attachments</span>
        </h3>
    </div>
    <?php if ($attachments === []): ?>
        <div class="content-body-thin">No Media Attached</div>
    <?php else: ?>
        <div class="content-body-thin">
            <?php foreach ($attachments as $attachment): ?>
                <?php
                $path = (string) $attachment["path"];
                $href =
                    preg_match("~^(https?://|/)~", $path) === 1
                        ? $path
                        : BASE_URL . "/" . $path;
                ?>
                <p>
                    <a href="<?= e(
                        $href,
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
    <?php endif; ?>
</div>

<form method="post" action="<?= e(BASE_URL . "/tickets/edit") ?>">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <input type="hidden" name="mail" value="<?= e($row["mail"]) ?>">
    <input type="hidden" name="question" value="<?= e($row["question"]) ?>">

    <div class="card4" style="margin-bottom: 40px; margin-top: 2%;">
        <div class="title-container">
            <h3 class="title">Update Answered</h3>
        </div>
        <div class="form-input">
            <label for="answer">Answer<small class="required-mark">*</small></label>
            <div class="input-group">
                <textarea placeholder id="answer" name="answer" required><?= e(
                    (string) $row["answer"],
                ) ?></textarea>
            </div>
        </div>
        <div class="input-box3">
            <button type="submit" class="btn-submit">Save Answer</button>
            <a href="<?= e(BASE_URL . "/tickets") ?>" class="back">Cancel</a>
        </div>
    </div>
</form>

<a href="<?= e(BASE_URL . "/tickets") ?>" class="back">Back to Tickets</a>

<?php admin_page_end(); ?>
