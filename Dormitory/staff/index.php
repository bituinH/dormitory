<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'staff') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=20260917-2">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="side-menu" id="side-menu" aria-label="Main menu">
            <div class="brand-block">
                <div class="brand-mark">D</div>
                <div class="menu-title">Dormitory Monitor</div>
            </div>
            <div class="profile-card">
                <div class="profile-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
                <div>
                    <span>Signed in as staff</span>
                    <strong><?= $username ?></strong>
                </div>
            </div>
            <div class="menu-label">Menu</div>
            <nav class="side-nav">
                <a href="index.php" class="active"><span class="nav-icon nav-grid"></span>Dashboard</a>
                <a href="checkin.php"><span class="nav-icon nav-plus"></span>Record check-in</a>
                <a href="maintenance.php"><span class="nav-icon nav-check"></span>View maintenance</a>
                <a href="rooms.php"><span class="nav-icon nav-list"></span>Room directory</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-staff">
        <header class="dashboard-header">
            <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div>
                <span class="eyebrow">Dormitory / Staff</span>
                <h1>Good day, <?= $username ?></h1>
                <p>Here is today's operations overview.</p>
            </div>
        </header>
        <section class="stats" aria-label="Staff summary">
            <article class="stat-card"><span>Occupied rooms</span><strong>42</strong><small>of 56 rooms</small></article>
            <article class="stat-card"><span>Open requests</span><strong>08</strong><small>3 need attention</small></article>
            <article class="stat-card"><span>Checked in today</span><strong>16</strong><small>+4 from yesterday</small></article>
        </section>
        <section class="content-grid">
            <article class="activity-card"><div class="section-heading"><h2>Today's tasks</h2><span class="role-badge">Staff access</span></div><ul class="task-list"><li><span class="status-dot"></span>Review room maintenance requests <b>08:30</b></li><li><span class="status-dot"></span>Confirm new resident arrivals <b>10:00</b></li><li><span class="status-dot"></span>Inspect common areas <b>14:00</b></li></ul></article>
            <article class="quick-card"><h2>Quick actions</h2><a href="checkin.php">Record check-in <span>+</span></a><a href="maintenance.php">View maintenance <span>+</span></a><a href="rooms.php">Room directory <span>+</span></a></article>
        </section>
    </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
