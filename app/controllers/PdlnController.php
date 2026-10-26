<?php
declare(strict_types=1);

class PdlnController extends Controller {
    
    private function initSession(): void {
        if (session_status() === PHP_SESSION_NONE && !session_start()) {
            throw new RuntimeException("Gagal memulai sesi untuk halaman PDLN.");
        }

        if (!isset($_SESSION['pengajuan'])) {
            $_SESSION['pengajuan'] = [
                ['id' => 101, 'kegiatan' => 'Enthufest',          'peserta' => 'Ahmad Zulfikar',     'jabatan' => 'Mahasiswa',  'tanggal' => '2026-04-09', 'status' => 'Baru Diajukan'],
                ['id' => 102, 'kegiatan' => 'Hackathon',          'peserta' => 'Dr. Siti Nurhaliza', 'jabatan' => 'Dosen',      'tanggal' => '2026-05-02', 'status' => 'Disetujui'],
                ['id' => 103, 'kegiatan' => 'Campfest',           'peserta' => 'Budi Santoso',       'jabatan' => 'Tendik',     'tanggal' => '2026-05-20', 'status' => 'Pending'],
                ['id' => 104, 'kegiatan' => 'Techno Expo',        'peserta' => 'Rina Marlina',       'jabatan' => 'Mahasiswa',  'tanggal' => '2026-06-03', 'status' => 'Revisi'],
                ['id' => 105, 'kegiatan' => 'Seminar Nasional',   'peserta' => 'Prof. Hendra Wijaya', 'jabatan' => 'Struktural', 'tanggal' => '2026-06-17', 'status' => 'Diproses'],
                ['id' => 106, 'kegiatan' => 'Robotics Challenge', 'peserta' => 'Dimas Prasetyo',     'jabatan' => 'Mahasiswa',  'tanggal' => '2026-06-28', 'status' => 'Ditolak'],
            ];
        }
    }

    public function detail(Request $req, array $params): void {
        $this->initSession();

        $basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $id = (int)($_GET['id'] ?? $_POST['pengajuan_id'] ?? 0);

        if (!$id) {
            Response::redirect(($basePath ?: '') . '/daftar-pdln');
            return;
        }

        $idx = array_search($id, array_column($_SESSION['pengajuan'], 'id'), true);
        if ($idx === false) {
            Response::redirect(($basePath ?: '') . '/daftar-pdln');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
            $statusList = ['Baru Diajukan', 'Pending', 'Diproses', 'Revisi', 'Disetujui', 'Ditolak'];
            $newStatus = (string)($_POST['status'] ?? '');
            $error = null;

            if (!in_array($newStatus, $statusList, true)) {
                $error = 'Status tidak valid.';
            }

            if (!$error) {
                $_SESSION['pengajuan'][$idx]['status'] = $newStatus;
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "Status pengajuan #{$id} berhasil diperbarui menjadi {$newStatus}."];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => $error];
            }

            Response::redirect(($basePath ?: '') . '/detail-pdln?id=' . $id);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'upload_lampiran') {
            $uploadDir = __DIR__ . '/../../public/uploads/lampiran/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $count = 0;
            if (!empty($_FILES['dokumen']['name']) && is_array($_FILES['dokumen']['name'])) {
                foreach ($_FILES['dokumen']['name'] as $key => $name) {
                    if (!empty($name) && ($_FILES['dokumen']['error'][$key] ?? 1) === UPLOAD_ERR_OK) {
                        $tmp = $_FILES['dokumen']['tmp_name'][$key];
                        $targetFile = $uploadDir . 'lampiran_' . $id . '_' . $key . '_' . bin2hex(random_bytes(4)) . '.pdf';
                        if (move_uploaded_file($tmp, $targetFile)) {
                            $count++;
                        }
                    }
                }
            }

            if ($count > 0) {
                $_SESSION['flash'] = ['type' => 'success', 'msg' => "{$count} berkas lampiran berhasil diunggah."];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => "Tidak ada berkas yang dipilih untuk diunggah."];
            }

            Response::redirect(($basePath ?: '') . '/detail-pdln?id=' . $id);
            return;
        }

        $item = $_SESSION['pengajuan'][$idx];

        Response::render("Dashboard.index", [
            "page" => "detail-pdln",
            "id_pengajuan" => $id,
            "pengajuan_item" => $item
        ]);
    }
}
