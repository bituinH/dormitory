<?php
session_start();

if (($_SESSION['role'] ?? '') !== 'user') {
    header('Location: ../index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice = 'Your message has been sent to staff.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Staff</title>
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
                <a href="payments.php"><span class="nav-icon nav-list"></span>Payment history</a>
                <a href="contact.php" class="active"><span class="nav-icon nav-phone"></span>Contact staff</a>
            </nav>
            <a class="side-logout" href="../logout.php"><span class="nav-icon nav-exit"></span>Log out</a>
        </aside>
        <main class="dashboard dashboard-user">
            <header class="dashboard-header">
                <button class="menu-toggle" type="button" aria-label="Open menu" aria-controls="side-menu" aria-expanded="false"><span></span><span></span><span></span></button>
                <div><span class="eyebrow">Dormitory / Resident</span><h1>Contact staff</h1><p>Send a direct message to the dormitory office or check contact details.</p></div>
            </header>
            <?php if ($notice): ?><div class="notice"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
            <section class="page-grid">
                <article class="form-card">
                    <h2>Message staff</h2>
                    <form class="form-grid" method="post" action="contact.php">
                        <div class="field"><label for="subject">Subject</label><input id="subject" name="subject" type="text" required></div>
                        <div class="field"><label for="message">Message</label><textarea id="message" name="message" required></textarea></div>
                        <button class="primary-button" type="submit">Send message</button>
                    </form>
                </article>
                <article class="full-card">
                    <h2>Office contact</h2>
                    <ul class="task-list">
                        <li><span class="status-dot"></span>Main office <b>8:00 AM - 5:00 PM</b></li>
                        <li><span class="status-dot"></span>Email support <b>dormitory@example.com</b></li>
                        <li><span class="status-dot"></span>Emergency desk <b>24 hours</b></li>
                    </ul>
                </article>
            </section>
        </main>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>
