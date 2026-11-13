<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("FAQ ID is required.", "/faqs");
}

$faq = new Faq();
$row = $faq->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("FAQ not found.", "/faqs");
}

page_start("Edit FAQ");
?>
<form method="post" action="/faqs/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="question">Question</label><br>
        <input id="question" name="question" type="text" value="<?= e(
            $row["question"],
        ) ?>" required>
    </p>
    <p>
        <label for="answer">Answer</label><br>
        <textarea id="answer" name="answer" rows="6" cols="60" required><?= e(
            $row["answer"],
        ) ?></textarea>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="/faqs">Back</a></p>
<?php page_end(); ?>
