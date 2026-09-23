<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'user') {
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
    <title>User Dashboard</title>
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
                    <span>Signed in as resident</span>
                    <strong><?= $username ?></strong>
                </div>
            </div>
            <div class="menu-label">Menu</div>
            <nav class="side-nav">
                <a href="index.php" class="active"><span class="nav-icon nav-grid"></span>Dashboard</a>
                <a href="request.php"><span class="nav-icon nav-plus"></span>Submit a request</a>
                <a href="payments.php"><span class="nav-icon nav-list"></span>Payment history</a>
                <a href="contact.php"><span class="nav-icon nav-phone"></span>Contact staff</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-user">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div>
                    <span class="eyebrow">Dormitory / Resident</span>
                    <h1>Welcome home, <?= $username ?></h1>
                    <p>Your dormitory information, all in one place.</p>
                </div>
            </header>
            <section class="resident-card"><div><span class="card-label">Your room</span><strong>Room 204 &middot; Block B</strong><p>Second floor &middot; 2 residents</p></div><span class="role-badge">Resident access</span></section>
            <section class="content-grid">
                <article class="activity-card"><div class="section-heading"><h2>Latest updates</h2></div><ul class="task-list"><li><span class="status-dot"></span>Water maintenance scheduled <b>Sep 20</b></li><li><span class="status-dot"></span>Rent payment received <b>Sep 01</b></li><li><span class="status-dot"></span>Community meeting announced <b>Aug 28</b></li></ul></article>
                <article class="quick-card"><h2>Your shortcuts</h2><a href="request.php">Submit a request <span>+</span></a><a href="payments.php">Payment history <span>+</span></a><a href="contact.php">Contact staff <span>+</span></a></article>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
