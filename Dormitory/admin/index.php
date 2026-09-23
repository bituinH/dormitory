<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <main class="dashboard dashboard-admin">
        <header class="dashboard-header">
            <div>
                <span class="eyebrow">Dormitory / Administration</span>
                <h1>Welcome back, <?= $username ?></h1>
                <p>Keep your residence running smoothly from one place.</p>
            </div>
            <a class="logout" href=" index.php?logout=1">Log out</a>
        </header>
        <section class="stats" aria-label="Admin summary">
            <article class="stat-card"><span>Total residents</span><strong>128</strong><small>+12 this month</small></article>
            <article class="stat-card"><span>Staff members</span><strong>14</strong><small>All shifts covered</small></article>
            <article class="stat-card"><span>Pending approvals</span><strong>05</strong><small>Require review</small></article>
        </section>
        <section class="content-grid">
            <article class="activity-card"><div class="section-heading"><h2>System activity</h2><span class="role-badge">Administrator access</span></div><ul class="task-list"><li><span class="status-dot"></span>New registration submitted <b>12 min ago</b></li><li><span class="status-dot"></span>Room allocation updated <b>1 hr ago</b></li><li><span class="status-dot"></span>Monthly report generated <b>Yesterday</b></li></ul></article>
            <article class="quick-card"><h2>Administration</h2><a href="#">Manage residents <span>+</span></a><a href="#">Review approvals <span>+</span></a><a href="#">System settings <span>+</span></a></article>
        </section>
    </main>
</body>
</html>
