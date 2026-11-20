<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

require_post();

$mail = trim((string) ($_POST["mail"] ?? ""));
$question = trim((string) ($_POST["question"] ?? ""));
$answer = trim((string) ($_POST["answer"] ?? ""));
$attachment = $_FILES["attachment"] ?? null;

$backUrl = BASE_URL . "/tickets/create";

if ($mail === "") {
    fail_page("Mail is required.", $backUrl);
}

if ($question === "") {
    fail_page("Question is required.", $backUrl);
}

$ticket = new Ticket();

try {
    $ticketId = $ticket->create(
        $mail,
        $question,
        $answer === "" ? null : $answer,
    );
} catch (Throwable $e) {
    fail_page($e->getMessage(), $backUrl);
}

$detailUrl = BASE_URL . "/tickets/details?id=" . urlencode($ticketId);
$saveUpload = static function (array $file, string $id): array {
    $error = (int) ($file["error"] ?? UPLOAD_ERR_NO_FILE);

    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException("Failed to upload file(s)");
    }

    if ((int) ($file["size"] ?? 0) > 2 * 1024 * 1024) {
        throw new RuntimeException("The attachment must be 2MB or smaller.");
    }

    $tmpName = (string) ($file["tmp_name"] ?? "");
    if ($tmpName === "" || !is_uploaded_file($tmpName)) {
        throw new RuntimeException("Failed to upload file(s)");
    }

    $types = [
        "application/pdf" => ["type" => "pdf", "ext" => "pdf"],
        "image/jpeg" => ["type" => "image", "ext" => "jpg"],
        "image/jpg" => ["type" => "image", "ext" => "jpg"],
        "image/png" => ["type" => "image", "ext" => "png"],
    ];
    $mime = mime_type($tmpName);
    if ($mime === false || !isset($types[$mime])) {
        throw new RuntimeException(
            "The attachment must be a PDF, JPG, or PNG file.",
        );
    }

    $fileName =
        $id . "-" . bin2hex(random_bytes(4)) . "." . $types[$mime]["ext"];
    $relativePath = "uploads/tickets/" . $fileName;
    $uploadDir = dirname(__DIR__, 2) . "/uploads/tickets";
    $destination = $uploadDir . "/" . $fileName;

    if (
        !is_dir($uploadDir) &&
        !mkdir($uploadDir, 0775, true) &&
        !is_dir($uploadDir)
    ) {
        throw new RuntimeException("Failed to upload file(s)");
    }

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException("Failed to upload file(s)");
    }

    return [
        "type" => $types[$mime]["type"],
        "path" => $relativePath,
        "absolute" => $destination,
    ];
};

if (
    is_array($attachment) &&
    (int) ($attachment["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE
) {
    try {
        $upload = $saveUpload($attachment, $ticketId);
    } catch (Throwable $e) {
        $ticket->delete($ticketId);
        fail_page($e->getMessage(), $backUrl);
    }

    try {
        (new TicketMedia())->create(
            $ticketId,
            $upload["type"],
            $upload["path"],
        );
    } catch (Throwable $e) {
        @unlink($upload["absolute"]);
        $ticket->delete($ticketId);
        fail_page($e->getMessage(), $backUrl);
    }
}

redirect("/");
