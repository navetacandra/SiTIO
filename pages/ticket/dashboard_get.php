<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

$ticket = new Ticket();
$rows = $ticket->all();

$title = "Tickets";
$active = "tickets";
require __DIR__ . "/../_layouts/header.php";
?>

<div class="between pdln-header">
    <div class="title-container">
        <h2 class="title">Tickets</h2>
        <p class="subtitle">Manage tickets for support and enquiries</p>
    </div>
    <button type="button" class="btn-create" onclick="window.location.href='<?= e(
        BASE_URL . "/tickets/create",
    ) ?>'">Create Tickets</button>
</div>

<div class="table-container" style="width: 100%">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Question</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows === []): ?>
                <tr>
                    <td colspan="5">No tickets.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($rows as $row): ?>
                <?php $answered =
                    trim((string) ($row["answer"] ?? "")) !== ""; ?>
                <?php
                $ticketId = (string) $row["ticket_id"];
                $detailUrl =
                    BASE_URL . "/tickets/details?id=" . urlencode($ticketId);
                $markDoneUrl = $detailUrl . "&focus=answer";
                ?>
                <tr>
                    <td>#<?= e(substr($ticketId, 0, 8)) ?></td>
                    <td><?= e($row["mail"]) ?></td>
                    <td><?= e($row["question"]) ?></td>
                    <td>
                        <div class="badge ticket-status <?= $answered
                            ? "done"
                            : "pending" ?>"><?= $answered
    ? "Done"
    : "Pending" ?>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn-detail" onclick="window.location.href='<?= e(
                            $detailUrl,
                        ) ?>'">Details</button>
                        <?php if (!$answered): ?>
                            <button type="button" class="btn-markdone-link" onclick="window.location.href='<?= e(
                                $markDoneUrl,
                            ) ?>'">Mark Done</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . "/../_layouts/footer.php"; ?>
