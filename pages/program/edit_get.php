<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Program.php";

$id = trim((string) ($_GET["id"] ?? ""));

if ($id === "") {
    fail_page("Program ID is required.", "/programs");
}

$program = new Program();
$row = $program->find($id);

if ($row === false) {
    http_response_code(404);
    fail_page("Program not found.", "/programs");
}

$startAt = date("Y-m-d\TH:i", strtotime($row["start_at"]));
$endAt = date("Y-m-d\TH:i", strtotime($row["end_at"]));

page_start("Edit Program");
?>
<form method="post" action="/programs/edit">
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <p>
        <label for="type">Type</label><br>
        <select id="type" name="type" required>
            <option value="inbound"<?= $row["program_type"] === "inbound"
                ? " selected"
                : "" ?>>Inbound</option>
            <option value="outbound"<?= $row["program_type"] === "outbound"
                ? " selected"
                : "" ?>>Outbound</option>
        </select>
    </p>
    <p>
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" value="<?= e(
            $row["program_name"],
        ) ?>" required>
    </p>
    <p>
        <label for="start_at">Start</label><br>
        <input id="start_at" name="start_at" type="datetime-local" value="<?= e(
            $startAt,
        ) ?>" required>
    </p>
    <p>
        <label for="end_at">End</label><br>
        <input id="end_at" name="end_at" type="datetime-local" value="<?= e(
            $endAt,
        ) ?>" required>
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="/programs">Back</a></p>
<?php page_end(); ?>
