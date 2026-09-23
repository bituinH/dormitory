<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/layout.php';
requireLogin();
$user = currentUser();
$stmt = $pdo->prepare("SELECT l.*, r.room_number, r.floor, r.capacity, r.occupied_beds FROM leases l JOIN rooms r ON r.id = l.room_id WHERE l.tenant_id = :tenant_id AND l.status = 'active' LIMIT 1");
$stmt->execute([':tenant_id' => $user['id']]);
$lease = $stmt->fetch();
$invoices = [];
$tickets = [];
if ($lease) {
    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE tenant_id = :tenant_id ORDER BY due_date DESC LIMIT 6');
    $stmt->execute([':tenant_id' => $user['id']]); $invoices = $stmt->fetchAll();
    $stmt = $pdo->prepare('SELECT * FROM maintenance_tickets WHERE tenant_id = :tenant_id ORDER BY created_at DESC LIMIT 6');
    $stmt->execute([':tenant_id' => $user['id']]); $tickets = $stmt->fetchAll();
}
pageHeader('Tenant Dashboard');
?>
<div class="mb-8"><p class="text-sm font-semibold uppercase tracking-wide text-teal-700">Tenant portal</p><h1 class="text-3xl font-bold">Welcome, <?= e($user['full_name']) ?></h1></div>
<?php if (!$lease): ?><div class="rounded-lg border border-amber-200 bg-amber-50 p-5 text-amber-900">No active lease is assigned to your account.</div><?php else: ?>
<div class="grid gap-4 md:grid-cols-3"><div class="rounded-lg bg-slate-900 p-5 text-white"><p class="text-sm text-slate-300">Room</p><p class="mt-2 text-2xl font-bold"><?= e($lease['room_number']) ?></p><p class="mt-1 text-sm">Floor <?= e((string) $lease['floor']) ?>, <?= e((string) $lease['occupied_beds']) ?>/<?= e((string) $lease['capacity']) ?> beds occupied</p></div><div class="rounded-lg border border-slate-200 bg-white p-5"><p class="text-sm text-slate-500">Monthly rent</p><p class="mt-2 text-2xl font-bold"><?= money($lease['monthly_rent']) ?></p></div><div class="rounded-lg border border-slate-200 bg-white p-5"><p class="text-sm text-slate-500">Lease started</p><p class="mt-2 text-2xl font-bold"><?= e($lease['start_date']) ?></p></div></div>
<div class="mt-8 grid gap-6 lg:grid-cols-2"><section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><div class="flex items-center justify-between"><h2 class="text-xl font-semibold">Recent bills</h2><a class="text-sm font-semibold text-teal-700" href="pay_bill.php">Pay a bill</a></div><div class="mt-4 space-y-3"><?php foreach ($invoices as $invoice): ?><div class="flex items-center justify-between border-b border-slate-100 pb-3"><div><p class="font-semibold"><?= e($invoice['month_year']) ?></p><p class="text-sm text-slate-500"><?= money($invoice['total_amount']) ?></p></div><?= badge($invoice['status']) ?></div><?php endforeach; ?><?php if (!$invoices): ?><p class="text-slate-500">No invoices found.</p><?php endif; ?></div></section><section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><div class="flex items-center justify-between"><h2 class="text-xl font-semibold">Maintenance</h2><a class="text-sm font-semibold text-teal-700" href="request.php">New request</a></div><div class="mt-4 space-y-3"><?php foreach ($tickets as $ticket): ?><div class="flex items-center justify-between border-b border-slate-100 pb-3"><div><p class="font-semibold"><?= e($ticket['category']) ?></p><p class="text-sm text-slate-500"><?= e($ticket['description']) ?></p></div><?= badge($ticket['status']) ?></div><?php endforeach; ?><?php if (!$tickets): ?><p class="text-slate-500">No maintenance tickets.</p><?php endif; ?></div></section></div>
<?php endif; ?>
<?php pageFooter(); ?>