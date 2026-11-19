<?php declare(strict_types=1);

require_once __DIR__ . "/../_layout.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Faq.php";

$faq = new Faq();
$rows = $faq->all();

$extraHead =
    '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">';

admin_page_start("FAQ", "faqs", $extraHead);
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
                    BASE_URL . "/faqs/delete",
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
        <h2 class="title">FAQ</h2>
        <p class="subtitle">Frequently Asked Questions</p>
    </div>
    <button type="button" class="btn-create" onclick="window.location.href='<?= e(
        BASE_URL . "/faqs/create",
    ) ?>'">Create FAQ</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Question</th>
                <th>Answer</th>
                <th style="width: 140px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows === []): ?>
                <tr>
                    <td colspan="3">No FAQs found.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= e($row["question"]) ?></td>
                    <td><?= nl2br(e($row["answer"])) ?></td>
                    <td>
                        <button type="button" class="yellow" onclick="window.location.href='<?= e(
                            BASE_URL . "/faqs/edit?id=" . $row["faq_id"],
                        ) ?>'">Edit</button>
                        <button type="button" class="red" onclick="showDelete('<?= e(
                            $row["faq_id"],
                        ) ?>')">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php admin_page_end(); ?>
