<?php
// app/views/Dashboard/login.view.php
$basePath = defined("BASE_URL") ? rtrim(BASE_URL, "/") : "";

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$isLogout = isset($_GET['logout']) || (($flash['type'] ?? '') === 'success');
$logoutNotice = $_GET['msg'] ?? ($flash['msg'] ?? 'Anda telah berhasil keluar dari sistem (Logout).');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiTIO PNJ</title>
    <link rel="stylesheet" href="<?= (defined("BASE_URL") ? BASE_URL : "") ?>/dist/style.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-[#d1ddc1] via-[#9ec2be] to-[#D9E7E8] bg-fixed font-sans text-slate-800 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Card Login -->
        <div class="bg-white/90 backdrop-blur-md border border-white/60 rounded-3xl shadow-xl p-8 sm:p-10 space-y-6">
            
            <!-- Logo & Brand Header -->
            <div class="text-center space-y-3">
                <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <img src="<?= (defined('BASE_URL') ? BASE_URL : '') ?>/assets/images/LogoPNJ.png" alt="Logo PNJ" class="w-16 h-16 object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">SiTIO PNJ</h1>
                    <p class="text-xs text-slate-500 font-medium mt-1">Sistem Informasi Tata Urusan Internasional Politeknik Negeri Jakarta</p>
                </div>
            </div>

            <!-- Flash Notification (Logout / Error) -->
            <?php if ($isLogout): ?>
                <div class="p-3.5 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span><?= htmlspecialchars((string)$logoutNotice) ?></span>
                </div>
            <?php elseif ($flash): ?>
                <div class="p-3.5 rounded-xl text-xs font-semibold flex items-center gap-2 bg-red-50 text-red-700 border border-red-200">
                    <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span><?= htmlspecialchars((string)($flash['msg'] ?? '')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form action="<?= ($basePath ?: '') ?>/login" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" id="username" name="username" value="admin" required
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#008C95] focus:border-transparent transition-all"
                               placeholder="Masukkan username atau email">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" id="password" name="password" value="password" required
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#008C95] focus:border-transparent transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-[#008C95] focus:ring-[#008C95]">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="text-[#008C95] hover:underline font-semibold">Lupa password?</a>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-4 bg-[#008C95] hover:bg-[#00737a] active:bg-[#005e64] text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                        <span>Masuk ke Sistem</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>

            <div class="text-center pt-2 border-t border-slate-100">
                <a href="<?= ($basePath ?: '') ?>/" class="text-xs text-slate-500 hover:text-slate-800 transition">
                    ← Kembali ke Dashboard Tanpa Login
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6 font-medium">
            &copy; <?= date('Y') ?> Kantor Urusan Internasional - Politeknik Negeri Jakarta
        </p>
    </div>

</body>
</html>
