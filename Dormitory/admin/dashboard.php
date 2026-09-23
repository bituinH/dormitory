<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/layout.php';
requireAdmin();

$occupancy = (float) ($pdo->query('SELECT COALESCE(SUM(occupied_beds) / NULLIF(SUM(capacity), 0) * 100, 0) FROM rooms')->fetchColumn());
$revenue = (float) ($pdo->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'approved'")->fetchColumn());
$pending = (float) ($pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM invoices WHERE status IN ('unpaid', 'overdue', 'pending')")->fetchColumn());
$openTickets = (int) ($pdo->query("SELECT COUNT(*) FROM maintenance_tickets WHERE status <> 'resolved'")->fetchColumn());
pageHeader('Admin Dashboard');
?>
<div class="mb-8"><p class="text-sm font-semibold uppercase tracking-wide text-teal-700">Administration</p><h1 class="text-3xl font-bold">Operations overview</h1></div>
<div class="grid gap-4 md:grid-cols-4">
<?php foreach ([['Occupancy', number_format($occupancy, 1) . '%'], ['Revenue', money($revenue)], ['Pending rent', money($pending)], ['Open tickets', (string) $openTickets]] as [$label, $value]): ?><div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500"><?= e($label) ?></p><p class="mt-2 text-2xl font-bold"><?= e($value) ?></p></div><?php endforeach; ?>
</div>
<div class="mt-8 rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-xl font-semibold">Administration</h2><div class="mt-4 flex flex-wrap gap-3"><a class="rounded bg-teal-700 px-4 py-2 font-semibold text-white" href="rooms.php">Manage rooms</a><a class="rounded bg-slate-900 px-4 py-2 font-semibold text-white" href="invoices.php">Generate invoices</a><a class="rounded border border-slate-300 px-4 py-2" href="payments.php">Review payments</a><a class="rounded border border-slate-300 px-4 py-2" href="maintenance.php">Maintenance</a></div></div>
<?php pageFooter(); ?>