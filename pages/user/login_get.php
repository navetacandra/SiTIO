<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

page_start("Login");
?>
<form method="post" action="login_post.php">
    <p>
        <label for="identifier">Email or phone</label><br>
        <input id="identifier" name="identifier" type="text" required>
    </p>
    <p>
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required>
    </p>
    <button type="submit">Login</button>
</form>

<p><a href="dashboard_get.php">Back</a></p>
<?php page_end(); ?>
