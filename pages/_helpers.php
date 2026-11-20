<?php declare(strict_types=1);

if (!defined("BASE_URL")) {
    define("BASE_URL", "/SiTIO");
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

/**
 * Ubah path lampiran menjadi URL yang bisa dibuka browser.
 * Path absolut (http://... atau /uploads/...) dipakai apa adanya,
 * path relatif diberi prefix BASE_URL.
 */
function media_url(string $path): string
{
    if ($path === "") {
        return "";
    }

    return preg_match("~^(https?://|/)~", $path) === 1
        ? $path
        : BASE_URL . "/" . ltrim($path, "/");
}

/**
 * Ambil nilai lama dari $_POST untuk repopulate form.
 * Sudah di-escape; aman dipakai di value="" maupun <textarea>.
 */
function old(string $field, mixed $alternative = null): string
{
    $value = $_POST[$field] ?? ($alternative ?? "");
    return e(is_scalar($value) ? (string) $value : "");
}

/**
 * Deteksi MIME asli sebuah file memakai finfo (server-side).
 */
function mime_type(string $path): string|false
{
    return (new finfo(FILEINFO_MIME_TYPE))->file($path);
}

function page_start(string $title): void
{
    echo "<!doctype html>\n";
    echo "<html lang=\"id\">\n";
    echo "<head><meta charset=\"utf-8\"><title>" .
        e($title) .
        "</title></head>\n";
    echo "<body>\n";
    echo "<h1>" . e($title) . "</h1>\n";
}

function page_end(): void
{
    echo "</body>\n</html>\n";
}

function require_post(): void
{
    if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST") {
        http_response_code(405);
        exit("Method Not Allowed");
    }
}

function redirect(string $path): never
{
    header("Location: " . BASE_URL . $path);
    exit();
}

function fail_page(string $message, string $back = ""): never
{
    http_response_code(400);
    page_start("Error");
    echo "<p>" . e($message) . "</p>\n";

    if ($back !== "") {
        echo '<p><a href="' . e($back) . '">Back</a></p>' . "\n";
    }

    page_end();
    exit();
}
