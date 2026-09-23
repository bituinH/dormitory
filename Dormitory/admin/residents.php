<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'Resident action saved for review.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Residents</title>
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
                <a href="residents.php" class="active"><span class="nav-icon nav-list"></span>Manage residents</a>
                <a href="approvals.php"><span class="nav-icon nav-check"></span>Review approvals</a>
                <a href="settings.php"><span class="nav-icon nav-gear"></span>System settings</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-admin">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Administration</span><h1>Manage residents</h1><p>Add residents, review room assignments, and keep resident records current.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="page-grid">
                <article class="form-card">
                    <h2>Add resident record</h2>
                    <form class="form-grid" method="post" action="residents.php">
                        <div class="form-row">
                            <div class="field"><label for="resident_name">Resident name</label><input id="resident_name" name="resident_name" type="text" required></div>
                            <div class="field"><label for="room">Room</label><input id="room" name="room" type="text" required></div>
                        </div>
                        <div class="form-row">
                            <div class="field"><label for="block">Block</label><input id="block" name="block" type="text" required></div>
                            <div class="field"><label for="status">Status</label><select id="status" name="status" required><option value="active">Active</option><option value="pending">Pending</option></select></div>
                        </div>
                        <button class="primary-button" type="submit">Save resident</button>
                    </form>
                </article>
                <article class="table-card alt">
                    <h2>Resident list</h2>
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Room</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            <tr><td>Richmond Nilo</td><td>204 B</td><td><span class="status-pill">Active</span></td><td><form method="post"><button class="small-button" type="submit">Update</button></form></td></tr>
                            <tr><td>Frieren</td><td>204 B</td><td><span class="status-pill">Active</span></td><td><form method="post"><button class="small-button" type="submit">Update</button></form></td></tr>
                            <tr><td>New Applicant</td><td>Pending</td><td><span class="status-pill pending">Pending</span></td><td><form method="post"><button class="small-button" type="submit">Review</button></form></td></tr>
                        </tbody>
                    </table>
                </article>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
