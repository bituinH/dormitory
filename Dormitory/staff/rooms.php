<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'staff') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'Room directory action completed.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Directory</title>
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
                <a href="checkin.php"><span class="nav-icon nav-plus"></span>Record check-in</a>
                <a href="maintenance.php"><span class="nav-icon nav-check"></span>View maintenance</a>
                <a href="rooms.php" class="active"><span class="nav-icon nav-list"></span>Room directory</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-staff">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Staff</span><h1>Room directory</h1><p>Review room occupancy and update room availability.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="table-card">
                <h2>Rooms</h2>
                <table class="data-table">
                    <thead><tr><th>Room</th><th>Residents</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td>204 B</td><td>2 of 2</td><td><span class="status-pill">Occupied</span></td><td><form method="post"><button class="small-button" type="submit">View</button></form></td></tr>
                        <tr><td>118 A</td><td>1 of 2</td><td><span class="status-pill pending">Available</span></td><td><form method="post"><button class="small-button" type="submit">Assign</button></form></td></tr>
                        <tr><td>312 C</td><td>0 of 2</td><td><span class="status-pill">Ready</span></td><td><form method="post"><button class="small-button" type="submit">Reserve</button></form></td></tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
