<?php
// app/views/daftar-tiket.view.php

$basePath = defined("BASE_URL") ? rtrim(BASE_URL, "/") : "";

if (class_exists('TiketController')) {
    TiketController::initSession();
} else {
    require_once __DIR__ . '/../controllers/TiketController.php';
    TiketController::initSession();
}

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$allTiket = $_SESSION['tiket'] ?? [];

// Hitung jumlah status
$countPending = count(array_filter($allTiket, fn($t) => ($t['status'] ?? '') === 'Pending'));
$countDone    = count(array_filter($allTiket, fn($t) => ($t['status'] ?? '') === 'Done'));

// Filter berdasarkan tab (Pending / Done / Semua)
$filter = strtolower(trim((string)($_GET['filter'] ?? 'all')));
if ($filter === 'pending') {
    $tiket = array_filter($allTiket, fn($t) => ($t['status'] ?? '') === 'Pending');
} elseif ($filter === 'done') {
    $tiket = array_filter($allTiket, fn($t) => ($t['status'] ?? '') === 'Done');
} else {
    $tiket = $allTiket;
}
?>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
    <!-- Header & Filter Tab (Sesuai Sketsa Gambar 1: Pending / Done) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Pertanyaan & Tiket</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar pertanyaan masuk dari pengguna yang membutuhkan tindak lanjut</p>
        </div>

        <!-- Filter Tab Kapsul Pending | Done -->
        <div class="inline-flex items-center p-1 bg-slate-100 rounded-2xl border border-slate-200 self-start sm:self-auto shadow-2xs">
            <a href="<?= ($basePath ?: '') ?>/daftar-tiket"
               class="px-4 py-1.5 rounded-xl text-xs font-semibold transition-all <?= $filter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
                Semua (<?= count($allTiket) ?>)
            </a>
            <a href="<?= ($basePath ?: '') ?>/daftar-tiket?filter=pending"
               class="px-4 py-1.5 rounded-xl text-xs font-semibold transition-all <?= $filter === 'pending' ? 'bg-[#f4a340] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
                Pending (<?= $countPending ?>)
            </a>
            <a href="<?= ($basePath ?: '') ?>/daftar-tiket?filter=done"
               class="px-4 py-1.5 rounded-xl text-xs font-semibold transition-all <?= $filter === 'done' ? 'bg-[#22c55e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
                Done (<?= $countDone ?>)
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    <?php if ($flash): ?>
        <div class="mt-4 px-4 py-3 rounded-lg text-sm <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' ?>">
            <?= $e($flash['msg']) ?>
        </div>
    <?php endif; ?>

    <!-- Tabel Daftar Tiket: Question, Status, Aksi (Sesuai Sketsa Gambar 1) -->
    <div class="overflow-x-auto mt-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-5 py-3.5 font-bold text-slate-700">Question</th>
                    <th scope="col" class="px-5 py-3.5 font-bold text-slate-700 w-36 text-center">Status</th>
                    <th scope="col" class="px-5 py-3.5 font-bold text-slate-700 w-52 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($tiket)): ?>
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-slate-400">
                            Tidak ada tiket pertanyaan pada filter ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tiket as $t): ?>
                        <?php $isDone = ($t['status'] ?? '') === 'Done'; ?>
                        <tr class="hover:bg-slate-50/80 transition-colors align-middle">
                            <!-- Kolom 1: Question -->
                            <td class="px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="font-mono text-xs text-[#008C95] font-bold bg-[#EEF7F8] border border-[#CDE8EB] px-2 py-0.5 rounded-md mt-0.5 shrink-0">
                                        #<?= (int)$t['id'] ?>
                                    </span>
                                    <div class="space-y-1 min-w-0">
                                        <a href="<?= ($basePath ?: '') ?>/detail-tiket?id=<?= (int)$t['id'] ?>"
                                           class="font-bold text-slate-900 text-sm hover:text-[#008C95] transition-colors block">
                                            <?= $e($t['judul'] ?? 'Pertanyaan') ?>
                                        </a>
                                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                            <?= $e($t['question'] ?? ($t['deskripsi'] ?? '')) ?>
                                        </p>
                                        <div class="flex items-center gap-3 text-[11px] text-slate-400 pt-1 flex-wrap">
                                            <span class="font-medium text-slate-600"><?= $e($t['pelapor'] ?? '') ?></span>
                                            <span>&lt;<?= $e($t['email'] ?? '') ?>&gt;</span>
                                            <?php if (!empty($t['media'])): ?>
                                                <span>•</span>
                                                <span class="inline-flex items-center gap-1 text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-md text-[10px]">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <?= count($t['media']) ?> Media
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 2: Status -->
                            <td class="px-5 py-4 text-center">
                                <?php if ($isDone): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ✓ Done
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        ● Pending
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom 3: Aksi [View] [Mark done] (Sesuai Sketsa Gambar 1) -->
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <!-- Tombol View -->
                                    <a href="<?= ($basePath ?: '') ?>/detail-tiket?id=<?= (int)$t['id'] ?>"
                                       class="px-3.5 py-1.5 text-xs font-semibold text-[#008C95] bg-[#EEF7F8] hover:bg-[#D9EFF1] border border-[#CDE8EB] rounded-xl inline-flex items-center gap-1 transition-colors">
                                        View
                                    </a>

                                    <!-- Tombol Mark done (jika belum Done) -->
                                    <?php if (!$isDone): ?>
                                        <form action="<?= ($basePath ?: '') ?>/mark-done-tiket" method="POST" class="inline">
                                            <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                            <button type="submit"
                                                    class="px-3.5 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 rounded-xl inline-flex items-center gap-1 transition-colors cursor-pointer"
                                                    title="Tandai tiket pertanyaan ini telah selesai dijawab">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Mark done
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Selesai
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
