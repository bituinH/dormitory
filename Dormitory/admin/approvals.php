<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $decision = $_POST['decision'] ?? 'updated';
    $notice = 'Approval request ' . htmlspecialchars($decision) . '.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Approvals</title>
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
                <a href="approvals.php" class="active"><span class="nav-icon nav-check"></span>Review approvals</a>
                <a href="settings.php"><span class="nav-icon nav-gear"></span>System settings</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-admin">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Administration</span><h1>Review approvals</h1><p>Approve or reject pending registrations, room changes, and dormitory requests.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= $notice ?></div><?php endif; ?>
            <section class="table-card">
                <h2>Pending approval queue</h2>
                <table class="data-table">
                    <thead><tr><th>Request</th><th>Submitted by</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td>New resident registration</td><td>Ana Reyes</td><td><span class="status-pill pending">Pending</span></td><td><form class="button-row" method="post"><button class="small-button" name="decision" value="approved" type="submit">Approve</button><button class="small-button" name="decision" value="rejected" type="submit">Reject</button></form></td></tr>
                        <tr><td>Room transfer request</td><td>Marco Lim</td><td><span class="status-pill pending">Pending</span></td><td><form class="button-row" method="post"><button class="small-button" name="decision" value="approved" type="submit">Approve</button><button class="small-button" name="decision" value="rejected" type="submit">Reject</button></form></td></tr>
                        <tr><td>Staff account update</td><td>Office Desk</td><td><span class="status-pill pending">Pending</span></td><td><form class="button-row" method="post"><button class="small-button" name="decision" value="approved" type="submit">Approve</button><button class="small-button" name="decision" value="rejected" type="submit">Reject</button></form></td></tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
