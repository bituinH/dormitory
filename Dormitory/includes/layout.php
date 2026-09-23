<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function pageHeader(string $title): void
{
    $user = currentUser();
    $name = $user['full_name'] ?? 'Dormitory';
    $role = $user['role'] ?? '';
    $flash = flash();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= e($title) ?> | Dormitory</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = { theme: { extend: { colors: { ink: '#17202a', leaf: '#0f766e', coral: '#dc6651' } } } };
        </script>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4">
            <a href="/Dormitory/<?= $role === 'admin' ? 'admin/dashboard.php' : 'tenant/dashboard.php' ?>" class="text-xl font-bold text-ink">Dormitory Management</a>
            <?php if ($user): ?>
                <nav class="flex flex-wrap items-center gap-2 text-sm">
                    <?php if ($role === 'admin'): ?>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/admin/dashboard.php">Dashboard</a>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/admin/rooms.php">Rooms</a>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/admin/invoices.php">Invoices</a>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/admin/payments.php">Payments</a>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/admin/maintenance.php">Maintenance</a>
                    <?php else: ?>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/tenant/dashboard.php">Dashboard</a>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/tenant/rooms.php">Available Rooms</a>
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="/Dormitory/tenant/pay_bill.php">Pay Bills</a>
                    <?php endif; ?>
                    <span class="rounded bg-slate-100 px-3 py-2"><?= e($name) ?></span>
                    <a class="rounded bg-ink px-3 py-2 font-semibold text-white" href="/Dormitory/logout.php">Logout</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <?php if ($flash): ?>
            <div class="mb-6 rounded border <?= $flash['type'] === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-rose-200 bg-rose-50 text-rose-800' ?> px-4 py-3">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
    <?php
}

function pageFooter(): void
{
    ?>
    </main>
    </body>
    </html>
    <?php
}

function badge(string $status): string
{
    $classes = [
        'available' => 'bg-emerald-100 text-emerald-800',
        'full' => 'bg-slate-200 text-slate-700',
        'maintenance' => 'bg-amber-100 text-amber-800',
        'unpaid' => 'bg-rose-100 text-rose-800',
        'pending' => 'bg-amber-100 text-amber-800',
        'paid' => 'bg-emerald-100 text-emerald-800',
        'overdue' => 'bg-red-100 text-red-800',
        'approved' => 'bg-emerald-100 text-emerald-800',
        'rejected' => 'bg-red-100 text-red-800',
        'open' => 'bg-sky-100 text-sky-800',
        'in_progress' => 'bg-violet-100 text-violet-800',
        'resolved' => 'bg-emerald-100 text-emerald-800',
        'active' => 'bg-emerald-100 text-emerald-800',
        'terminated' => 'bg-slate-200 text-slate-700',
    ];
    $class = $classes[$status] ?? 'bg-slate-100 text-slate-800';
    return '<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ' . $class . '">' . e(str_replace('_', ' ', ucfirst($status))) . '</span>';
}
