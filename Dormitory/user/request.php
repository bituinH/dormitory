<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'user') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'Your request has been submitted to staff.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Request</title>
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
                <a href="request.php" class="active"><span class="nav-icon nav-plus"></span>Submit a request</a>
                <a href="payments.php"><span class="nav-icon nav-list"></span>Payment history</a>
                <a href="contact.php"><span class="nav-icon nav-phone"></span>Contact staff</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-user">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Resident</span><h1>Submit a request</h1><p>Send maintenance, room, or general dormitory requests to staff.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="page-grid">
                <article class="form-card">
                    <h2>Request details</h2>
                    <form class="form-grid" method="post" action="request.php">
                        <div class="form-row">
                            <div class="field"><label for="category">Category</label><select id="category" name="category" required><option value="maintenance">Maintenance</option><option value="room">Room concern</option><option value="billing">Billing</option><option value="other">Other</option></select></div>
                            <div class="field"><label for="priority">Priority</label><select id="priority" name="priority" required><option value="normal">Normal</option><option value="urgent">Urgent</option></select></div>
                        </div>
                        <div class="field"><label for="message">Message</label><textarea id="message" name="message" required></textarea></div>
                        <button class="primary-button" type="submit">Submit request</button>
                    </form>
                </article>
                <article class="table-card alt">
                    <h2>Recent requests</h2>
                    <table class="data-table">
                        <thead><tr><th>Request</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td>Water maintenance</td><td>Sep 20</td><td><span class="status-pill pending">Scheduled</span></td></tr>
                            <tr><td>Room cabinet repair</td><td>Sep 12</td><td><span class="status-pill">Resolved</span></td></tr>
                            <tr><td>Internet concern</td><td>Sep 05</td><td><span class="status-pill">Resolved</span></td></tr>
                        </tbody>
                    </table>
                </article>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
