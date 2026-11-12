<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

page_start("Create Ticket");
?>
<form method="post" action="create_post.php">
    <p>
        <label for="mail">Mail</label><br>
        <input id="mail" name="mail" type="email" required>
    </p>
    <p>
        <label for="question">Question</label><br>
        <textarea id="question" name="question" rows="6" cols="60" required></textarea>
    </p>
    <p>
        <label for="answer">Answer</label><br>
        <textarea id="answer" name="answer" rows="6" cols="60"></textarea>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="dashboard_get.php">Back</a></p>
<?php page_end(); ?>
