<nav>
    <label for="aside-toggler">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="burger-menu">
            <path d="M4 5h16"></path>
            <path d="M4 12h16"></path>
            <path d="M4 19h16"></path>
        </svg>
    </label>
    <div class="user-info">
        <label for="user-action">
            <div class="initial">U</div>
            <p>User</p>
        </label>
        <div class="user-action">
            <input type="checkbox" id="user-action" style="display: none;">
            <a href="<?= e(
                BASE_URL . "/profile",
            ) ?>" onclick="window.location.href='<?= e(
    BASE_URL . "/profile",
) ?>'">Profile</a>
            <a href="#">Logout</a>
        </div>
    </div>
</nav>
