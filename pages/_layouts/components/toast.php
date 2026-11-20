<?php declare(strict_types=1);

$toastType = $toastType ?? "info";

$toastIcon = json_encode(
    (string) $toastType,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
);
$toastTitle = json_encode(
    (string) $toastMessage,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
);
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
            icon: <?= $toastIcon ?>,
            title: <?= $toastTitle ?>
        });
    });
</script>
