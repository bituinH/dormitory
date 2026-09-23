<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'System settings saved.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=20260917-2">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="side-menu" id="side-menu" aria-label="Main menu">
            <div class="brand-block"><div class="brand-mark">D</div><div class="menu-title">Dormitory Monitor</div></div>
            <div class="profile-card"><div class="profile-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div><div><span>Signed in as admin</span><strong><?= $username ?></strong></div></div>
            <div class="menu-label">Menu</div>
            <nav class="side-nav">
                <a href="index.php"><span class="nav-icon nav-grid"></span>Dashboard</a>
                <a href="residents.php"><span class="nav-icon nav-list"></span>Manage residents</a>
                <a href="approvals.php"><span class="nav-icon nav-check"></span>Review approvals</a>
                <a href="settings.php" class="active"><span class="nav-icon nav-gear"></span>System settings</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-admin">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Administration</span><h1>System settings</h1><p>Configure dormitory notices, office hours, and system preferences.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="form-card">
                <h2>Dormitory preferences</h2>
                <form class="form-grid" method="post" action="settings.php">
                    <div class="form-row">
                        <div class="field"><label for="office_hours">Office hours</label><input id="office_hours" name="office_hours" type="text" value="8:00 AM - 5:00 PM" required></div>
                        <div class="field"><label for="quiet_hours">Quiet hours</label><input id="quiet_hours" name="quiet_hours" type="text" value="10:00 PM - 6:00 AM" required></div>
                    </div>
                    <div class="field"><label for="announcement">Dashboard announcement</label><textarea id="announcement" name="announcement" required>Keep common areas clean and report maintenance issues early.</textarea></div>
                    <button class="primary-button" type="submit">Save settings</button>
                </form>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
