<?php
declare(strict_types=1);

class TiketController extends Controller {

    public static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE && !session_start()) {
            throw new RuntimeException("Gagal memulai sesi untuk tiket.");
        }

        if (!isset($_SESSION['tiket']) || !isset($_SESSION['tiket'][0]['email'])) {
            $_SESSION['tiket'] = [
                [
                    'id' => 501,
                    'email' => 'rina.marlina@mhsw.pnj.ac.id',
                    'pelapor' => 'Rina Marlina',
                    'jabatan' => 'Mahasiswa',
                    'kategori' => 'Teknis',
                    'judul' => 'Form pengajuan PDLN tidak bisa disubmit',
                    'question' => 'Form pengajuan PDLN saya selalu gagal ketika menekan tombol submit. Pesan kesalahan tidak muncul di layar, melainkan halaman hanya me-reload tanpa menyimpan data. Mohon bantuannya segera.',
                    'media' => [
                        'screenshot_error_form.png',
                        'screenshot_console_log.png'
                    ],
                    'tanggal' => '2026-10-20',
                    'status' => 'Pending',
                    'answer' => ''
                ],
                [
                    'id' => 502,
                    'email' => 'siti.nurhaliza@dosen.pnj.ac.id',
                    'pelapor' => 'Dr. Siti Nurhaliza',
                    'jabatan' => 'Dosen',
                    'kategori' => 'Administrasi',
                    'judul' => 'Data paspor salah tercatat',
                    'question' => 'Nomor paspor saya di sistem tercatat X1234567, padahal paspor dinas terbaru saya adalah X9876543 dengan masa berlaku hingga tahun 2028. Bagaimana cara memperbarui data ini?',
                    'media' => [
                        'scan_paspor_lama.png',
                        'scan_paspor_baru.png'
                    ],
                    'tanggal' => '2026-10-18',
                    'status' => 'Pending',
                    'answer' => 'Menunggu dokumen pembanding dari pelapor untuk verifikasi data ke bagian keimigrasian.'
                ],
                [
                    'id' => 503,
                    'email' => 'dimas.prasetyo@mhsw.pnj.ac.id',
                    'pelapor' => 'Dimas Prasetyo',
                    'jabatan' => 'Mahasiswa',
                    'kategori' => 'Layanan',
                    'judul' => 'Status pengajuan tidak berubah',
                    'question' => 'Pengajuan keberangkatan Robotics Challenge sudah lebih dari seminggu belum ada update persetujuan. Apakah berkas administrasi saya ada yang kurang?',
                    'media' => [
                        'bukti_submission.png'
                    ],
                    'tanggal' => '2026-10-10',
                    'status' => 'Done',
                    'answer' => 'Pengajuan telah ditinjau dan disetujui oleh Wakil Direktur IV pada tanggal 15 Oktober 2026.'
                ],
                [
                    'id' => 504,
                    'email' => 'hendra.wijaya@pnj.ac.id',
                    'pelapor' => 'Prof. Hendra Wijaya',
                    'jabatan' => 'Struktural',
                    'kategori' => 'Teknis',
                    'judul' => 'Tidak bisa unduh surat tugas',
                    'question' => 'Ketika mencoba mengunduh file surat tugas yang sudah ditandatangani secara digital, sistem memunculkan error 404 (file not found). Mohon dicek filenya di server.',
                    'media' => [
                        'error_404_download.png'
                    ],
                    'tanggal' => '2026-10-22',
                    'status' => 'Pending',
                    'answer' => ''
                ],
                [
                    'id' => 505,
                    'email' => 'budi.santoso@tendik.pnj.ac.id',
                    'pelapor' => 'Budi Santoso',
                    'jabatan' => 'Tendik',
                    'kategori' => 'Lainnya',
                    'judul' => 'Usulan penambahan kolom instansi',
                    'question' => 'Mohon dipertimbangkan untuk menambahkan kolom asal instansi pada tabel rekapitulasi outbound agar proses rekap lebih efisien.',
                    'media' => [],
                    'tanggal' => '2026-10-05',
                    'status' => 'Done',
                    'answer' => 'Usulan telah kami terima dan kolom instansi sudah ditambahkan pada rilis sistem SiTIO versi terbaru.'
                ],
                [
                    'id' => 506,
                    'email' => 'ahmad.zulfikar@mhsw.pnj.ac.id',
                    'pelapor' => 'Ahmad Zulfikar',
                    'jabatan' => 'Mahasiswa',
                    'kategori' => 'Layanan',
                    'judul' => 'Jadwal keberangkatan berubah',
                    'question' => 'Pihak penyelenggara seminar memundurkan jadwal kegiatan dari tanggal 15 ke 17 Juli. Bagaimana prosedur revisi berkas di sistem?',
                    'media' => [
                        'surat_perubahan_jadwal.png'
                    ],
                    'tanggal' => '2026-10-23',
                    'status' => 'Pending',
                    'answer' => 'Silakan upload surat penundaan resmi pada bagian dokumen lampiran pengajuan Anda.'
                ],
            ];
        }
    }

    public function detail(Request $req, array $params): void {
        self::initSession();

        $basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $id = (int)($_GET['id'] ?? $_POST['tiket_id'] ?? 0);

        if (!$id) {
            Response::redirect(($basePath ?: '') . '/daftar-tiket');
            return;
        }

        $idx = array_search($id, array_column($_SESSION['tiket'], 'id'), true);
        if ($idx === false) {
            Response::redirect(($basePath ?: '') . '/daftar-tiket');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'jawab_tiket') {
            $answer = trim((string)($_POST['answer'] ?? ''));
            $status = (string)($_POST['status'] ?? 'Done');

            $_SESSION['tiket'][$idx]['answer'] = $answer;
            $_SESSION['tiket'][$idx]['status'] = $status;
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Jawaban untuk tiket #{$id} berhasil disimpan dan status diperbarui menjadi {$status}."];

            Response::redirect(($basePath ?: '') . '/detail-tiket?id=' . $id);
            return;
        }

        $item = $_SESSION['tiket'][$idx];

        Response::render("Dashboard.index", [
            "page" => "detail-tiket",
            "id_tiket" => $id,
            "tiket_item" => $item
        ]);
    }

    public function markDone(Request $req, array $params): void {
        self::initSession();

        $basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        $idx = array_search($id, array_column($_SESSION['tiket'], 'id'), true);
        if ($idx !== false) {
            $_SESSION['tiket'][$idx]['status'] = 'Done';
            $_SESSION['flash'] = ['type' => 'success', 'msg' => "Tiket #{$id} berhasil ditandai selesai (Done)."];
        }

        Response::redirect(($basePath ?: '') . '/daftar-tiket');
    }
}
