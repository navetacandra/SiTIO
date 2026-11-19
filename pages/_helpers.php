<?php declare(strict_types=1);

if (!defined("BASE_URL")) {
    define("BASE_URL", "/SiTIO");
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
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
    header("Location: " . $path);
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
