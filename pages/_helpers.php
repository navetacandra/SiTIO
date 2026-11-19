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
 * URL daftar ticket media, opsional difilter per ticket.
 */
function ticket_media_url(string $ticketId = ""): string
{
    $url = BASE_URL . "/ticket-media";

    if ($ticketId !== "") {
        $url .= "?ticket_id=" . urlencode($ticketId);
    }

    return $url;
}

/**
 * Batas ukuran file lampiran (5MB).
 */
const TICKET_UPLOAD_MAX_SIZE = 5 * 1024 * 1024;

/**
 * MIME yang diizinkan beserta tipe ticket_media dan ekstensinya.
 */
const TICKET_UPLOAD_TYPES = [
    "application/pdf" => ["type" => "pdf", "ext" => "pdf"],
    "image/jpeg" => ["type" => "image", "ext" => "jpg"],
    "image/png" => ["type" => "image", "ext" => "png"],
];

/**
 * Apakah request menyertakan file yang benar-benar diunggah.
 */
function has_ticket_upload(mixed $file): bool
{
    return is_array($file) &&
        (int) ($file["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
}

/**
 * Validasi dan simpan file lampiran ke uploads/tickets.
 *
 * @return array{type: string, path: string, absolute: string}
 * @throws RuntimeException ketika file tidak valid atau gagal disimpan.
 */
function save_ticket_upload(array $file, string $ticketId): array
{
    $error = (int) ($file["error"] ?? UPLOAD_ERR_NO_FILE);

    if ($error !== UPLOAD_ERR_OK) {
        $messages = [
            UPLOAD_ERR_INI_SIZE => "The attachment is larger than the server limit.",
            UPLOAD_ERR_FORM_SIZE => "The attachment is larger than the allowed size.",
            UPLOAD_ERR_PARTIAL => "The attachment was only partially uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "The server is missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "The server failed to write the attachment.",
            UPLOAD_ERR_EXTENSION => "The upload was stopped by a server extension.",
        ];

        throw new RuntimeException(
            $messages[$error] ?? "The attachment could not be uploaded.",
        );
    }

    if ((int) ($file["size"] ?? 0) > TICKET_UPLOAD_MAX_SIZE) {
        throw new RuntimeException("The attachment must be 5MB or smaller.");
    }

    $tmpName = (string) ($file["tmp_name"] ?? "");

    if ($tmpName === "" || !is_uploaded_file($tmpName)) {
        throw new RuntimeException("The attachment could not be uploaded.");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($tmpName);

    if (!isset(TICKET_UPLOAD_TYPES[$mime])) {
        throw new RuntimeException(
            "The attachment must be a PDF, JPG, or PNG file.",
        );
    }

    $ext = TICKET_UPLOAD_TYPES[$mime]["ext"];
    $fileName = $ticketId . "-" . bin2hex(random_bytes(4)) . "." . $ext;
    $relativePath = "uploads/tickets/" . $fileName;
    $uploadDir = dirname(__DIR__) . "/uploads/tickets";
    $destination = $uploadDir . "/" . $fileName;

    if (
        !is_dir($uploadDir) &&
        !mkdir($uploadDir, 0775, true) &&
        !is_dir($uploadDir)
    ) {
        throw new RuntimeException("The upload folder could not be created.");
    }

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException("The attachment could not be saved.");
    }

    return [
        "type" => TICKET_UPLOAD_TYPES[$mime]["type"],
        "path" => $relativePath,
        "absolute" => $destination,
    ];
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
