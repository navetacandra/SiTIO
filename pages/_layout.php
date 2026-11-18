<?php declare(strict_types=1);

require_once __DIR__ . "/_helpers.php";

/**
 * Layout admin (sidebar + navbar) yang dipakai halaman-halaman CRUD.
 * Markup diambil dari desain HTML yang sudah jadi.
 */
function admin_page_start(
    string $title,
    string $active = "",
    string $extraHead = "",
): void {
    $link = static fn(string $key): string => $active === $key
        ? "nav-link active"
        : "nav-link"; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="navetacandra,dhaniwira,malikfadillah,rizkacahya,kesyarizqia">
    <link rel="stylesheet" href="<?= e(BASE_URL . "/style.css") ?>">
    <link rel="icon" type="image/png" href="<?= e(BASE_URL . "/logo.png") ?>">
    <title><?= e($title) ?> - SiTIO PNJ</title>
    <?= $extraHead ?>
</head>
<body>
<aside>
    <input type="checkbox" id="aside-toggler" style="display: none;">
    <label for="aside-toggler"><span class="close">&times;</span></label>
    <div class="brand">
        <img src="<?= e(BASE_URL . "/logo.png") ?>" alt="SiTIO Icon">
        <div class="brand-text">
            <span>SiTIO PNJ</span>
            <span>Sistem Terpusat International Office</span>
        </div>
    </div>
    <div class="menu">
        <span class="menu-title">Menu</span>
        <a href="<?= e(BASE_URL . "/dashboard") ?>" class="<?= $link(
    "dashboard",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            </svg>
            <span>Dashboard</span>
        </a>
        <a href="<?= e(BASE_URL . "/pdln") ?>" class="<?= $link("pdln") ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="6" cy="19" r="3"></circle>
                <path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"></path>
                <circle cx="18" cy="5" r="3"></circle>
            </svg>
            <span>PDLN</span>
        </a>
        <a href="<?= e(BASE_URL . "/inbound") ?>" class="<?= $link(
    "inbound",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 22h20"></path>
                <path d="M3.77 10.77 2 9l2-4.5 1.1.55c.55.28.9.84.9 1.45s.35 1.17.9 1.45L8 8.5l3-6 1.05.53a2 2 0 0 1 1.09 1.52l.72 5.4a2 2 0 0 0 1.09 1.52l4.4 2.2c.42.22.78.55 1.01.96l.6 1.03c.49.88-.06 1.98-1.06 2.1l-1.18.15c-.47.06-.95-.02-1.37-.24L4.29 11.15a2 2 0 0 1-.52-.38Z"></path>
            </svg>
            <span>Inbound</span>
        </a>
        <a href="<?= e(BASE_URL . "/outbound") ?>" class="<?= $link(
    "outbound",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 22h20"></path>
                <path d="M6.36 17.4 4 17l-2-4 1.1-.55a2 2 0 0 1 1.8 0l.17.1a2 2 0 0 0 1.8 0L8 12 5 6l.9-.45a2 2 0 0 1 2.09.2l4.02 3a2 2 0 0 0 2.1.2l4.19-2.06a2.41 2.41 0 0 1 1.73-.17L21 7a1.4 1.4 0 0 1 .87 1.99l-.38.76c-.23.46-.6.84-1.07 1.08L7.58 17.2a2 2 0 0 1-1.22.18Z"></path>
            </svg>
            <span>Outbound</span>
        </a>
        <a href="<?= e(BASE_URL . "/paspor-dinas") ?>" class="<?= $link(
    "paspor-dinas",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"></path>
                <path d="m9 9.5 2 2 4-4"></path>
            </svg>
            <span>Paspor Dinas</span>
        </a>
        <a href="<?= e(BASE_URL . "/tickets") ?>" class="<?= $link(
    "tickets",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                <path d="M13 5v2"></path>
                <path d="M13 17v2"></path>
                <path d="M13 11v2"></path>
            </svg>
            <span>Tickets</span>
        </a>
    </div>
    <div class="menu">
        <span class="menu-title">Master Data</span>
        <a href="<?= e(BASE_URL . "/inbound-programs") ?>" class="<?= $link(
    "inbound-programs",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                <path d="M13 5v2"></path>
                <path d="M13 17v2"></path>
                <path d="M13 11v2"></path>
            </svg>
            <span>Inbound Programs</span>
        </a>
        <a href="<?= e(BASE_URL . "/departments") ?>" class="<?= $link(
    "departments",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 21v-3a2 2 0 0 0-4 0v3"></path>
                <path d="M18 12h.01"></path>
                <path d="M18 16h.01"></path>
                <path d="M22 7a1 1 0 0 0-1-1h-2a2 2 0 0 1-1.143-.359L13.143 2.36a2 2 0 0 0-2.286-.001L6.143 5.64A2 2 0 0 1 5 6H3a1 1 0 0 0-1 1v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2z"></path>
                <path d="M6 12h.01"></path>
                <path d="M6 16h.01"></path>
                <circle cx="12" cy="10" r="2"></circle>
            </svg>
            <span>Departments</span>
        </a>
        <a href="<?= e(BASE_URL . "/study-programs") ?>" class="<?= $link(
    "study-programs",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                <path d="M22 10v6"></path>
                <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
            </svg>
            <span>Study Programs</span>
        </a>
        <a href="<?= e(BASE_URL . "/programs") ?>" class="<?= $link(
    "programs",
) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                <path d="M13 5v2"></path>
                <path d="M13 17v2"></path>
                <path d="M13 11v2"></path>
            </svg>
            <span>Programs</span>
        </a>
        <a href="<?= e(BASE_URL . "/faqs") ?>" class="<?= $link("faqs") ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path>
                <path d="M13 5v2"></path>
                <path d="M13 17v2"></path>
                <path d="M13 11v2"></path>
            </svg>
            <span>FAQ</span>
        </a>
    </div>
</aside>
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
<main id="dashboard">
    <div class="container">
<?php
}

function admin_page_end(): void
{
    ?>
    </div>
</main>
</body>
</html>
<?php
}
