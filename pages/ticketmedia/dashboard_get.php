<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/TicketMedia.php";

$ticketId = trim((string) ($_GET["ticket_id"] ?? ""));
$ticketMedia = new TicketMedia();
$rows = $ticketMedia->all($ticketId === "" ? null : $ticketId);

admin_page_start("Ticket Media", "tickets");
?>

<div class="pdln-header">
    <div class="title-container">
        <h2 class="title">Ticket Media</h2>
        <p class="subtitle"><?= $ticketId === ""
            ? "All ticket attachments"
            : "Attachments for ticket #" . e(substr($ticketId, 0, 8)) ?></p>
    </div>
    <div class="input-box3" style="margin-top: 0">
        <button type="button" class="btn-create" onclick="window.location.href='<?= e(
            BASE_URL .
                "/ticket-media/create" .
                ($ticketId === "" ? "" : "?ticket_id=" . urlencode($ticketId)),
        ) ?>'">Add Media</button>
        <?php if ($ticketId !== ""): ?>
            <a href="<?= e(
                BASE_URL . "/tickets/details?id=" . urlencode($ticketId),
            ) ?>" class="back">Back to Ticket</a>
        <?php endif; ?>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Ticket</th>
                <th>Type</th>
                <th>File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows === []): ?>
                <tr>
                    <td colspan="4">No ticket media.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($rows as $row): ?>
                <?php
                $rowTicketId = (string) $row["ticket_id"];
                $path = (string) $row["path"];
                $editUrl =
                    BASE_URL .
                    "/ticket-media/edit?ticket_id=" .
                    urlencode($rowTicketId) .
                    "&path=" .
                    urlencode($path);
                ?>
                <tr>
                    <td>
                        <a href="<?= e(
                            BASE_URL .
                                "/tickets/details?id=" .
                                urlencode($rowTicketId),
                        ) ?>">#<?= e(substr($rowTicketId, 0, 8)) ?></a>
                    </td>
                    <td>
                        <span class="badge <?= $row["type"] === "pdf"
                            ? "mandatory"
                            : "verification" ?>"><?= e($row["type"]) ?></span>
                    </td>
                    <td>
                        <a href="<?= e(
                            media_url($path),
                        ) ?>" target="_blank" rel="noopener"><?= e(
    basename($path),
) ?></a>
                    </td>
                    <td>
                        <a href="<?= e($editUrl) ?>" class="btn-edit">Edit</a>
                        <form method="post" action="<?= e(
                            BASE_URL . "/ticket-media/delete",
                        ) ?>" style="display: inline" onsubmit="return confirm('Delete this attachment?');">
                            <input type="hidden" name="ticket_id" value="<?= e(
                                $rowTicketId,
                            ) ?>">
                            <input type="hidden" name="path" value="<?= e(
                                $path,
                            ) ?>">
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php admin_page_end(); ?>
