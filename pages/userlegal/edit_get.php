<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/UserLegal.php";

$userId = trim((string) ($_GET["user_id"] ?? ""));

if ($userId === "") {
    fail_page("User ID is required.", "dashboard_get.php");
}

$userLegal = new UserLegal();
$row = $userLegal->find($userId);

if ($row === false) {
    http_response_code(404);
    fail_page("User legal data not found.", "dashboard_get.php");
}

page_start("Edit User Legal");
?>
<form method="post" action="edit_post.php">
    <input type="hidden" name="user_id" value="<?= e($userId) ?>">
    <p>
        <label>User ID</label><br>
        <input type="text" value="<?= e($row["user_id"]) ?>" readonly>
    </p>
    <p>
        <label for="nik">NIK</label><br>
        <input id="nik" name="nik" type="text" value="<?= e(
            (string) $row["nik"],
        ) ?>">
    </p>
    <p>
        <label for="nim">NIM</label><br>
        <input id="nim" name="nim" type="text" value="<?= e(
            (string) $row["nim"],
        ) ?>">
    </p>
    <p>
        <label for="nip">NIP</label><br>
        <input id="nip" name="nip" type="text" value="<?= e(
            (string) $row["nip"],
        ) ?>">
    </p>
    <p>
        <label for="paspor_num">Passport number</label><br>
        <input id="paspor_num" name="paspor_num" type="text" value="<?= e(
            (string) $row["paspor_num"],
        ) ?>">
    </p>
    <p>
        <label for="ktp">KTP</label><br>
        <input id="ktp" name="ktp" type="text" value="<?= e(
            (string) $row["ktp"],
        ) ?>">
    </p>
    <p>
        <label for="paspor">Passport</label><br>
        <input id="paspor" name="paspor" type="text" value="<?= e(
            (string) $row["paspor"],
        ) ?>">
    </p>
    <button type="submit">Update</button>
</form>

<p><a href="dashboard_get.php">Back</a></p>
<?php page_end(); ?>
