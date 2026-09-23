<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'staff') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'Check-in record saved.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Check-in</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=20260917-2">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="side-menu" id="side-menu" aria-label="Main menu">
            <div class="brand-block"><div class="brand-mark">D</div><div class="menu-title">Dormitory Monitor</div></div>
            <div class="profile-card"><div class="profile-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div><div><span>Signed in as staff</span><strong><?= $username ?></strong></div></div>
            <div class="menu-label">Menu</div>
            <nav class="side-nav">
                <a href="index.php"><span class="nav-icon nav-grid"></span>Dashboard</a>
                <a href="checkin.php" class="active"><span class="nav-icon nav-plus"></span>Record check-in</a>
                <a href="maintenance.php"><span class="nav-icon nav-check"></span>View maintenance</a>
                <a href="rooms.php"><span class="nav-icon nav-list"></span>Room directory</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-staff">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Staff</span><h1>Record check-in</h1><p>Log resident arrivals and confirm room assignment details.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="page-grid">
                <article class="form-card">
                    <h2>New check-in</h2>
                    <form class="form-grid" method="post" action="checkin.php">
                        <div class="form-row">
                            <div class="field"><label for="resident_name">Resident name</label><input id="resident_name" name="resident_name" type="text" required></div>
                            <div class="field"><label for="room">Room</label><input id="room" name="room" type="text" required></div>
                        </div>
                        <div class="form-row">
                            <div class="field"><label for="arrival">Arrival time</label><input id="arrival" name="arrival" type="time" required></div>
                            <div class="field"><label for="condition">Room condition</label><select id="condition" name="condition" required><option value="ready">Ready</option><option value="needs-cleaning">Needs cleaning</option><option value="maintenance">Maintenance needed</option></select></div>
                        </div>
                        <button class="primary-button" type="submit">Save check-in</button>
                    </form>
                </article>
                <article class="table-card alt">
                    <h2>Today's arrivals</h2>
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Room</th><th>Time</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td>Frieren</td><td>204 B</td><td>09:20</td><td><span class="status-pill">Checked in</span></td></tr>
                            <tr><td>Richmond Nilo</td><td>204 B</td><td>10:15</td><td><span class="status-pill">Checked in</span></td></tr>
                            <tr><td>Ana Reyes</td><td>Pending</td><td>13:00</td><td><span class="status-pill pending">Expected</span></td></tr>
                        </tbody>
                    </table>
                </article>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
