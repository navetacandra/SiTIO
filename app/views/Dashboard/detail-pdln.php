<?php

$basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$rawId = (int)($pengajuan_item['id'] ?? ($id_pengajuan ?? 101));

$detail = [
    'id' => '#' . $rawId,
    'raw_id' => $rawId,
    'nama_kegiatan' => $pengajuan_item['kegiatan'] ?? 'Enthufest 2026',
    'pemohon' => ($pengajuan_item['peserta'] ?? 'Dr. Budi Santoso') . ' (' . ($pengajuan_item['jabatan'] ?? 'Dosen') . ')',
    'status' => $pengajuan_item['status'] ?? 'Diproses', 
    'tgl_berangkat' => $pengajuan_item['tanggal'] ?? '2026-11-15',
    'tgl_pulang' => !empty($pengajuan_item['tanggal']) ? date('Y-m-d', strtotime($pengajuan_item['tanggal'] . ' +7 days')) : '2026-11-20',
];

$peserta = [
    [
        'id' => 'P-' . str_pad((string)$rawId, 3, '0', STR_PAD_LEFT),
        'nama' => $pengajuan_item['peserta'] ?? 'Ahmad Zulfikar',
        'jabatan' => $pengajuan_item['jabatan'] ?? 'Mahasiswa',
    ],
];

// Daftar 11 dokumen lampiran
$dokumenLampiran = [
    'TOR Kegiatan',
    'Memo Persetujuan',
    'Bukti Registrasi',
    'Surat Pernyataan Pembiayaan',
    'Jadwal Kegiatan',
    'Clearance Letter',
    'Brosur',
    'Draft MOU/MOA',
    'Undangan',
    'Surat Keterangan Relevansi',
    'Surat Tugas',
];

$statusList = ['Baru Diajukan', 'Pending', 'Diproses', 'Revisi', 'Disetujui', 'Ditolak'];
$statusBadge = [
    'Baru Diajukan' => 'bg-sky-100 text-sky-700',
    'Pending'       => 'bg-amber-100 text-amber-700',
    'Diproses'      => 'bg-indigo-100 text-indigo-700',
    'Revisi'        => 'bg-rose-100 text-rose-700',
    'Disetujui'     => 'bg-green-100 text-green-700',
    'Ditolak'       => 'bg-red-100 text-red-700',
];

$statusClean = strtolower(trim((string)$detail['status']));
$canUpload = in_array($statusClean, ['pending', 'revisi'], true);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<div class="max-w-6xl mx-auto space-y-6">
    <?php if ($flash): ?>
        <div class="px-4 py-3 rounded-lg text-sm <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
            <?= htmlspecialchars($flash['msg']) ?>
        </div>
    <?php endif; ?>

    <div class="flex items-center gap-4">
        <a href="<?= ($basePath ?: '') ?>/daftar-pdln" class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <h1 class="text-xl font-bold text-slate-800">Detail Pengajuan <?= htmlspecialchars($detail['id']) ?></h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <h2 class="text-base font-semibold text-slate-800 mb-4 border-b pb-2">Informasi Kegiatan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                <div>
                    <p class="text-slate-500 font-medium">Nama Kegiatan</p>
                    <p class="text-slate-800 font-semibold mt-0.5"><?= htmlspecialchars($detail['nama_kegiatan']) ?></p>
                </div>
                <div>
                    <p class="text-slate-500 font-medium">Pemohon</p>
                    <p class="text-slate-800 mt-0.5"><?= htmlspecialchars($detail['pemohon']) ?></p>
                </div>
                <div>
                    <p class="text-slate-500 font-medium">Tanggal Pelaksanaan</p>
                    <p class="text-slate-800 mt-0.5">
                        <?= date('d M Y', strtotime($detail['tgl_berangkat'])) ?> - <?= date('d M Y', strtotime($detail['tgl_pulang'])) ?>
                    </p>
                </div>
                <div>
                    <p class="text-slate-500 font-medium">Status Saat Ini</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-medium <?= $statusBadge[$detail['status']] ?? 'bg-slate-100 text-slate-700' ?>">
                        <?= htmlspecialchars($detail['status']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-xl shadow-sm p-5">
            <h2 class="text-base font-semibold text-slate-800 mb-4 border-b pb-2">Tindakan</h2>
            <form action="<?= ($basePath ?: '') ?>/detail-pdln?id=<?= (int)$detail['raw_id'] ?>" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="pengajuan_id" value="<?= (int)$detail['raw_id'] ?>">

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Update Status</label>
                    <select id="status" name="status" class="block w-full text-sm text-slate-700 border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#008C95]/30 focus:border-[#008C95]">
                        <?php foreach ($statusList as $s): ?>
                            <option value="<?= $s ?>" <?= $detail['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white bg-[#008C95] hover:bg-[#00747c] rounded-xl transition-colors cursor-pointer shadow-sm">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <div class="flex items-center mb-4 px-1">
            <h3 class="text-base font-semibold text-slate-800">Daftar Peserta & Lampiran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-medium rounded-tl-lg">ID Peserta</th>
                        <th scope="col" class="px-4 py-3 font-medium">Nama Peserta</th>
                        <th scope="col" class="px-4 py-3 font-medium">Jabatan</th>
                        <th scope="col" class="px-4 py-3 font-medium rounded-tr-lg">Daftar Lampiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($peserta)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada data peserta.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($peserta as $p): ?>
                            <tr class="hover:bg-slate-50 align-middle">
                                <td class="px-4 py-3 font-mono text-xs text-slate-500"><?= htmlspecialchars($p['id']) ?></td>
                                <td class="px-4 py-3 font-medium text-slate-800"><?= htmlspecialchars($p['nama']) ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($p['jabatan']) ?></td>
                                <td class="px-4 py-3">
                                    <button type="button" data-open-lampiran
                                        data-peserta="<?= htmlspecialchars($p['nama']) ?>"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#008C95] bg-[#EEF7F8] hover:bg-[#D9EFF1] border border-[#CDE8EB] rounded-xl transition-colors cursor-pointer shadow-xs">
                                        <svg class="w-4 h-4 text-[#008C95]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                        </svg>
                                        <span>Lihat Dokumen</span>
                                        <span class="inline-flex items-center justify-center bg-[#008C95] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">11</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-dokumen-lampiran" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-xs p-4">
    <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Header Bertema SiTIO PNJ (#008C95) -->
        <div class="bg-gradient-to-r from-[#008C95] via-[#009da7] to-[#00747c] px-6 py-5 flex items-center justify-between text-white">
            <div>
                <h3 class="text-2xl font-bold tracking-tight text-white">Dokumen Lampiran</h3>
                <?php if ($canUpload): ?>
                    <p class="text-xs text-white/90 mt-0.5 font-medium">Status: <span class="underline font-bold"><?= htmlspecialchars($detail['status']) ?></span> — Silakan unggah berkas revisi yang dibutuhkan</p>
                <?php endif; ?>
            </div>
            <button type="button" data-close-lampiran class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition-colors cursor-pointer" aria-label="Tutup modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="<?= ($basePath ?: '') ?>/detail-pdln?id=<?= (int)$detail['raw_id'] ?>" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            <input type="hidden" name="action" value="upload_lampiran">
            <input type="hidden" name="pengajuan_id" value="<?= (int)$detail['raw_id'] ?>">

            <div class="p-5 overflow-y-auto max-h-[62vh] space-y-3.5 bg-white flex-1">
                <?php foreach ($dokumenLampiran as $idxKey => $doc): ?>
                    <div class="border border-slate-200/90 rounded-2xl p-4 bg-white hover:bg-slate-50/80 transition-colors shadow-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <svg class="w-5 h-5 text-slate-800 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block"><?= htmlspecialchars($doc) ?></span>
                                    <?php if ($canUpload): ?>
                                        <span class="text-[11px] text-slate-400 block font-normal file-status-text" id="status-doc-<?= $idxKey ?>">
                                            Belum ada file baru dipilih
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                <?php if ($canUpload): ?>
                                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl cursor-pointer transition-colors shadow-2xs" title="Pilih berkas baru">
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <span>Pilih File</span>
                                        <input type="file" name="dokumen[<?= $idxKey ?>]" accept="application/pdf" class="hidden lampiran-file-input" data-label-id="status-doc-<?= $idxKey ?>">
                                    </label>
                                <?php endif; ?>

                                <a href="<?= ($basePath ?: '') ?>/uploads/lampiran/sample_dokumen.pdf" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#008C95] bg-[#EEF7F8] hover:bg-[#D9EFF1] border border-[#CDE8EB] rounded-xl transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    <span>Buka</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-100 flex items-center justify-between">
                <?php if ($canUpload): ?>
                    <span class="text-xs text-slate-400">*Format PDF maks. 5 MB</span>
                    <div class="flex items-center gap-2">
                        <button type="button" data-close-lampiran class="px-5 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-2xl transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-[#008C95] hover:bg-[#00747c] rounded-2xl transition-colors cursor-pointer shadow-sm">
                            Simpan Lampiran
                        </button>
                    </div>
                <?php else: ?>
                    <div class="ml-auto">
                        <button type="button" data-close-lampiran class="px-7 py-2.5 text-sm font-semibold text-[#008C95] bg-[#EEF7F8] hover:bg-[#D9EFF1] rounded-2xl transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('modal-dokumen-lampiran');
    if (!modal) return;

    document.body.appendChild(modal);

    const toggle = (show) => {
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
    };

    document.querySelectorAll('[data-open-lampiran]').forEach(btn => {
        btn.addEventListener('click', () => toggle(true));
    });

    document.querySelectorAll('[data-close-lampiran]').forEach(btn => {
        btn.addEventListener('click', () => toggle(false));
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) toggle(false);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            toggle(false);
        }
    });

    document.querySelectorAll('.lampiran-file-input').forEach(input => {
        input.addEventListener('change', function() {
            const labelId = this.dataset.labelId;
            const label = document.getElementById(labelId);
            if (label && this.files && this.files.length > 0) {
                const file = this.files[0];
                const sizeKb = (file.size / 1024).toFixed(0);
                label.textContent = '✓ ' + file.name + ' (' + sizeKb + ' KB)';
                label.classList.remove('text-slate-400');
                label.classList.add('text-emerald-600', 'font-semibold');
            }
        });
    });
})();
</script>
