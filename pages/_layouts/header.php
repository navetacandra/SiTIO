<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";

$title = $title ?? "SiTIO";
$active = $active ?? "";
$extraHead = $extraHead ?? "";

$link = static fn(string $key): string => $active === $key ||
($key === "faqs" && $active === "faq")
    ? "nav-link active"
    : "nav-link";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="navetacandra,dhaniwira,malikfadillah,rizkacahya,kesyarizqia">
    <link rel="stylesheet" href="<?= e(
        BASE_URL . "/style.css?v=" . filemtime(__DIR__ . "/../../style.css"),
    ) ?>">
    <link rel="icon" type="image/png" href="<?= e(BASE_URL . "/logo.png") ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title><?= e($title) ?> - SiTIO PNJ</title>
    <?= $extraHead ?>
</head>
<body>
<?php require __DIR__ . "/components/sidebar.php"; ?>
<?php require __DIR__ . "/components/navbar.php"; ?>
<main id="dashboard">
    <div class="container">
