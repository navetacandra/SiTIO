<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

$title = "Create FAQ";
$active = "faqs";
require __DIR__ . "/../_layouts/header.php";
?>

<form method="post" action="<?= e(BASE_URL . "/faqs/create") ?>">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Create FAQ</h3>
        </div>
        <div class="form-input">
            <label for="question">Question<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="Question" id="question" name="question" value="<?= old(
                    "question",
                ) ?>" required>
            </div>
        </div>
        <div class="form-input">
            <label for="answer">Answer<small class="required-mark">*</small></label>
            <div class="input-group">
                <textarea placeholder="Answer" id="answer" name="answer" rows="6" required><?= old(
                    "answer",
                ) ?></textarea>
            </div>
        </div>
    </div>
    <div class="input-box3">
        <button type="submit" class="btn-submit">Create FAQ</button>
        <a href="<?= e(BASE_URL . "/faqs") ?>" class="back">Cancel</a>
    </div>
</form>

<?php require __DIR__ . "/../_layouts/footer.php"; ?>
