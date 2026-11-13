<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

require_post();

$id = trim((string) ($_POST["id"] ?? ""));

if ($id === "") {
    fail_page("Ticket ID is required.", BASE_URL . "/tickets");
}

$ticket = new Ticket();

try {
    $ticket->delete($id);
} catch (Throwable $e) {
    fail_page($e->getMessage(), BASE_URL . "/tickets");
}

redirect(BASE_URL . "/tickets");
