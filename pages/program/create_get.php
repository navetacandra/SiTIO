<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

page_start("Create Program");
?>
<form method="post" action="/programs/create">
    <p>
        <label for="type">Type</label><br>
        <select id="type" name="type" required>
            <option value="inbound">Inbound</option>
            <option value="outbound">Outbound</option>
        </select>
    </p>
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" required>
    </p>
    <p>
        <label for="start_at">Start</label><br>
        <input id="start_at" name="start_at" type="datetime-local" required>
    </p>
    <p>
        <label for="end_at">End</label><br>
        <input id="end_at" name="end_at" type="datetime-local" required>
    </p>
    <button type="submit">Save</button>
</form>

<p><a href="/programs">Back</a></p>
<?php page_end(); ?>
