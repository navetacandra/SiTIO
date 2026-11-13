<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

page_start("Create FAQ");
?>
<form method="post" action="/faqs/create">
    <p>
        <label for="question">Question</label><br>
        <input id="question" name="question" type="text" required>
    </p>
    <p>
        <label for="answer">Answer</label><br>
        <textarea id="answer" name="answer" rows="6" cols="60" required></textarea>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="/faqs">Back</a></p>
<?php page_end(); ?>
