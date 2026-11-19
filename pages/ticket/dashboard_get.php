<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Ticket.php";

$ticket = new Ticket();
$rows = $ticket->all();

$extraHead =
    '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">';

admin_page_start("Tickets", "tickets", $extraHead);
?>

<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            confirmButtonColor: 'var(--red)',
            html: `
                <form method="POST" action="<?= e(
                    BASE_URL . "/tickets/delete",
                ) ?>" id="delete-form" style="display: none;">
                    <input type="hidden" name="id" value="${id}">
                </form>
            `,
            preConfirm: () => {
                sessionStorage.setItem('deleteSuccess', 'true');
                document.querySelector('#delete-form').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (sessionStorage.getItem('deleteSuccess') === 'true') {
            sessionStorage.removeItem('deleteSuccess');

            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            }).fire({
                icon: 'success',
                title: 'Deleted successfully'
            });
        }
    });
</script>

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
                $editUrl =
                    BASE_URL . "/tickets/edit?id=" . urlencode($ticketId);
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
                        <button type="button" class="btn-edit" onclick="window.location.href='<?= e(
                            $editUrl,
                        ) ?>'">Edit</button>
                        <button type="button" class="btn-detail" onclick="window.location.href='<?= e(
                            $detailUrl,
                        ) ?>'">Details</button>
                        <button type="button" class="btn-delete" onclick="showDelete('<?= e(
                            $ticketId,
                        ) ?>')">Delete</button>
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

<?php admin_page_end(); ?>
