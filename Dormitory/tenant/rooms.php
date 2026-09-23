<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/layout.php';

requireLogin();

if ((currentUser()['role'] ?? '') !== 'tenant') {
    redirectWith('/Dormitory/admin/dashboard.php', 'error', 'Only tenant accounts can book rooms.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? null);
    $roomId = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT);
    $tenantId = (int) currentUser()['id'];

    if (!$roomId) {
        redirectWith('rooms.php', 'error', 'Invalid room selected.');
    }

    try {
        $pdo->beginTransaction();

        $statement = $pdo->prepare("SELECT id FROM leases WHERE tenant_id = :tenant_id AND status = 'active' LIMIT 1");
        $statement->execute([':tenant_id' => $tenantId]);
        if ($statement->fetch()) {
            $pdo->rollBack();
            redirectWith('rooms.php', 'error', 'You already have an active room assignment.');
        }

        $statement = $pdo->prepare('SELECT id, capacity, occupied_beds, monthly_rate, status FROM rooms WHERE id = :room_id FOR UPDATE');
        $statement->execute([':room_id' => $roomId]);
        $room = $statement->fetch();
        if (!$room || $room['status'] !== 'available' || (int) $room['occupied_beds'] >= (int) $room['capacity']) {
            $pdo->rollBack();
            redirectWith('rooms.php', 'error', 'That room is no longer available.');
        }

        $statement = $pdo->prepare("INSERT INTO leases (tenant_id, room_id, start_date, monthly_rent, status) VALUES (:tenant_id, :room_id, CURRENT_DATE, :monthly_rent, 'active')");
        $statement->execute([
            ':tenant_id' => $tenantId,
            ':room_id' => $roomId,
            ':monthly_rent' => $room['monthly_rate'],
        ]);

        $statement = $pdo->prepare("UPDATE rooms SET occupied_beds = occupied_beds + 1, status = CASE WHEN occupied_beds >= capacity THEN 'full' ELSE 'available' END WHERE id = :room_id");
        $statement->execute([':room_id' => $roomId]);
        $pdo->commit();
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        redirectWith('rooms.php', 'error', 'The room could not be booked. Please try again.');
    }

    redirectWith('dashboard.php', 'success', 'Room booked successfully.');
}

$statement = $pdo->query("SELECT id, room_number, floor, capacity, occupied_beds, monthly_rate, status, (capacity - occupied_beds) AS available_beds FROM rooms WHERE status = 'available' AND occupied_beds < capacity ORDER BY floor, room_number");
$rooms = $statement->fetchAll();

pageHeader('Available Rooms');
?>
<div class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-wide text-teal-700">Tenant portal</p>
    <h1 class="text-3xl font-bold">Available rooms</h1>
    <p class="mt-2 text-slate-600">Browse rooms that currently have open beds.</p>
</div>

<?php if (!$rooms): ?>
    <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 text-amber-900">
        There are no rooms with available beds right now.
    </div>
<?php else: ?>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($rooms as $room): ?>
            <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Floor <?= e((string) $room['floor']) ?></p>
                        <h2 class="mt-1 text-2xl font-bold">Room <?= e($room['room_number']) ?></h2>
                    </div>
                    <?= badge($room['status']) ?>
                </div>
                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <dt class="text-slate-500">Monthly rate</dt>
                        <dd class="font-semibold"><?= money($room['monthly_rate']) ?></dd>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <dt class="text-slate-500">Available beds</dt>
                        <dd class="font-semibold"><?= e((string) $room['available_beds']) ?> of <?= e((string) $room['capacity']) ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Occupied beds</dt>
                        <dd class="font-semibold"><?= e((string) $room['occupied_beds']) ?></dd>
                    </div>
                </dl>
                <form class="mt-6" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="room_id" value="<?= e((string) $room['id']) ?>">
                    <button class="w-full rounded bg-teal-700 px-4 py-2 font-semibold text-white hover:bg-teal-800" type="submit">Book this room</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php pageFooter(); ?>