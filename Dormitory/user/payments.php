<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'user') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'Payment receipt request sent.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=20260917-2">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="side-menu" id="side-menu" aria-label="Main menu">
            <div class="brand-block"><div class="brand-mark">D</div><div class="menu-title">Dormitory Monitor</div></div>
            <div class="profile-card"><div class="profile-avatar"><?= strtoupper(substr($username, 0, 1)) ?></div><div><span>Signed in as resident</span><strong><?= $username ?></strong></div></div>
            <div class="menu-label">Menu</div>
            <nav class="side-nav">
                <a href="index.php"><span class="nav-icon nav-grid"></span>Dashboard</a>
                <a href="request.php"><span class="nav-icon nav-plus"></span>Submit a request</a>
                <a href="payments.php" class="active"><span class="nav-icon nav-list"></span>Payment history</a>
                <a href="contact.php"><span class="nav-icon nav-phone"></span>Contact staff</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-user">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Resident</span><h1>Payment history</h1><p>Review payments and request receipt copies from the dormitory office.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="table-card">
                <h2>Payments</h2>
                <table class="data-table">
                    <thead><tr><th>Date</th><th>Description</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td>Sep 01, 2026</td><td>Monthly rent</td><td>PHP 4,500</td><td><span class="status-pill">Paid</span></td><td><form method="post"><button class="small-button" type="submit">Request receipt</button></form></td></tr>
                        <tr><td>Aug 01, 2026</td><td>Monthly rent</td><td>PHP 4,500</td><td><span class="status-pill">Paid</span></td><td><form method="post"><button class="small-button" type="submit">Request receipt</button></form></td></tr>
                        <tr><td>Jul 01, 2026</td><td>Monthly rent</td><td>PHP 4,500</td><td><span class="status-pill">Paid</span></td><td><form method="post"><button class="small-button" type="submit">Request receipt</button></form></td></tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
