<?php declare(strict_types=1);

require_once __DIR__ . "/../_helpers.php";
require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../models/Department.php";

$department = new Department();
$departments = $department->all();

$title = "Departments";
$active = "departments";
require __DIR__ . "/../_layouts/header.php";
?>

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
                    BASE_URL . "/departments/delete",
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
        <h2 class="title">Departments</h2>
        <p class="subtitle">Academic Departments</p>
    </div>
    <button type="button" class="btn-create" onclick="window.location.href='<?= e(
        BASE_URL . "/departments/create",
    ) ?>'">Create Department</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($departments === []): ?>
                <tr>
                    <td colspan="2">No departments.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($departments as $row): ?>
                <tr>
                    <td><?= e($row["department_name"]) ?></td>
                    <td>
                        <button type="button" class="btn-edit" onclick="window.location.href='<?= e(
                            BASE_URL .
                                "/departments/edit?id=" .
                                $row["department_id"],
                        ) ?>'">Edit</button>
                        <button type="button" class="btn-delete" onclick="showDelete('<?= e(
                            $row["department_id"],
                        ) ?>')">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . "/../_layouts/footer.php"; ?>
