<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("FAQ ID is required.", BASE_URL . "/faqs");
}

$faq = new Faq();
$row = $faq->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("FAQ not found.", BASE_URL . "/faqs");
}

admin_page_start("Edit FAQ", "faqs");
?>

<form method="post" action="<?= e(BASE_URL . "/faqs/edit") ?>">
    <div class="card">
        <div class="title-container">
            <h3 class="title">Edit FAQ</h3>
        </div>
        <div class="form-input">
            <label for="question">Question<small class="required-mark">*</small></label>
            <div class="input-group">
                <input type="text" placeholder="Question" id="question" name="question" value="<?= e(
                    $row["question"],
                ) ?>" required>
            </div>
        </div>
        <div class="form-input">
            <label for="answer">Answer<small class="required-mark">*</small></label>
            <div class="input-group">
                <textarea placeholder="Answer" id="answer" name="answer" rows="6" required><?= e(
                    $row["answer"],
                ) ?></textarea>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <div class="input-box3">
        <button type="submit" class="btn-submit">Update FAQ</button>
        <a href="<?= e(BASE_URL . "/faqs") ?>" class="back">Cancel</a>
    </div>
</form>

<?php admin_page_end(); ?>
