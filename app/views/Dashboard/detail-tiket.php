<?php

$basePath = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$t = $tiket_item ?? [
    'id' => 501,
    'email' => 'rina.marlina@mhsw.pnj.ac.id',
    'pelapor' => 'Rina Marlina',
    'jabatan' => 'Mahasiswa',
    'kategori' => 'Teknis',
    'judul' => 'Form pengajuan PDLN tidak bisa disubmit',
    'question' => 'Form pengajuan PDLN saya selalu gagal ketika menekan tombol submit.',
    'media' => ['screenshot_error_form.png', 'screenshot_console_log.png'],
    'tanggal' => '2026-10-20',
    'status' => 'Pending',
    'answer' => ''
];

$media = $t['media'] ?? [];
$isDone = ($t['status'] ?? '') === 'Done';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<div class="max-w-5xl mx-auto space-y-6">
    <?php if ($flash): ?>
        <div class="px-4 py-3 rounded-lg text-sm <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
            <?= htmlspecialchars($flash['msg']) ?>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <a href="<?= ($basePath ?: '') ?>/daftar-tiket" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Detail Tiket #<?= (int)$t['id'] ?></h1>
        </div>
        <div>
            <?php if ($isDone): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Status: Done
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Status: Pending
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
            <svg class="w-4 h-4 text-[#008C95]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Email Pelapor</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-[#EEF7F8] border border-[#CDE8EB] flex items-center justify-center text-[#008C95] font-bold text-base shadow-2xs">
                    <?= strtoupper(substr($t['pelapor'] ?? 'P', 0, 1)) ?>
                </div>
                <div>
                    <a href="mailto:<?= htmlspecialchars($t['email'] ?? '') ?>" class="text-base font-bold text-[#008C95] hover:underline">
                        <?= htmlspecialchars($t['email'] ?? 'pelapor@pnj.ac.id') ?>
                    </a>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        <?= htmlspecialchars($t['pelapor'] ?? '') ?> · <span class="text-slate-700"><?= htmlspecialchars($t['jabatan'] ?? '') ?></span>
                    </p>
                </div>
            </div>
            <div class="text-right text-xs text-slate-400">
                <p>Kategori: <strong class="text-slate-700"><?= htmlspecialchars($t['kategori'] ?? 'Layanan') ?></strong></p>
                <p class="mt-0.5">Tanggal: <?= date('d M Y', strtotime($t['tanggal'] ?? 'now')) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Question</span>
        </div>
        <h2 class="text-lg font-bold text-slate-900 mb-3">
            <?= htmlspecialchars($t['judul'] ?? 'Pertanyaan Tiket') ?>
        </h2>
        <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line font-normal">
            <?= htmlspecialchars($t['question'] ?? ($t['deskripsi'] ?? '')) ?>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Media (Lampiran Gambar)</span>
            </div>
            <span class="text-xs text-slate-500 font-medium"><?= count($media) ?> lampiran</span>
        </div>

        <?php if (!empty($media)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($media as $imgFile): ?>
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50/70 hover:bg-slate-100 transition-colors group">
                        <div class="relative overflow-hidden rounded-lg bg-white border border-slate-200 aspect-video flex items-center justify-center">
                            <img src="<?= ($basePath ?: '') ?>/uploads/tiket/<?= rawurlencode($imgFile) ?>"
                                 alt="<?= htmlspecialchars($imgFile) ?>"
                                 class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                            <a href="<?= ($basePath ?: '') ?>/uploads/tiket/<?= rawurlencode($imgFile) ?>" target="_blank"
                               class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Buka Ukuran Penuh
                            </a>
                        </div>
                        <p class="text-xs font-medium text-slate-700 mt-2 truncate flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                            <span class="truncate"><?= htmlspecialchars($imgFile) ?></span>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-sm">
                Tidak ada lampiran media untuk pertanyaan ini.
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
        <form action="<?= ($basePath ?: '') ?>/detail-tiket?id=<?= (int)$t['id'] ?>" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="jawab_tiket">
            <input type="hidden" name="tiket_id" value="<?= (int)$t['id'] ?>">

            <div class="flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <span>Answer (Jawaban / Tanggapan)</span>
                </div>
                <div class="flex items-center gap-2">
                    <label for="status" class="text-xs font-semibold text-slate-600">Update Status:</label>
                    <select name="status" id="status" class="text-xs font-medium border border-slate-300 rounded-lg px-2.5 py-1 text-slate-700 bg-slate-50 focus:ring-2 focus:ring-[#008C95]/30 focus:border-[#008C95] cursor-pointer">
                        <option value="Done" <?= $isDone ? 'selected' : '' ?>>Done (Selesai)</option>
                        <option value="Pending" <?= !$isDone ? 'selected' : '' ?>>Pending (Menunggu)</option>
                    </select>
                </div>
            </div>

            <div>
                <textarea name="answer" rows="5" required
                    placeholder="Tuliskan jawaban atau solusi atas pertanyaan pelapor di sini..."
                    class="block w-full text-sm text-slate-800 border border-slate-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-[#008C95]/40 focus:border-[#008C95] transition-all leading-relaxed"><?= htmlspecialchars($t['answer'] ?? '') ?></textarea>
            </div>

            <div class="flex items-center justify-between pt-2 flex-wrap gap-3">
                <p class="text-xs text-slate-400">Jawaban akan disimpan dan tiket ditandai dengan status yang dipilih.</p>
                
                <button type="submit"
                        class="px-7 py-2.5 bg-[#008C95] hover:bg-[#00747c] text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition-all cursor-pointer inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    Submit Jawaban
                </button>
            </div>
        </form>
    </div>
</div>
