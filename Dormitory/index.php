<?php
declare(strict_types=1);

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$identifier = trim((string) ($_POST['identifier'] ?? ''));
	$password = $_POST['password'] ?? '';
	$statement = $pdo->prepare('SELECT id, full_name, username, email, password_hash, role FROM users WHERE username = :username OR email = :email LIMIT 1');
	$statement->execute([':username' => $identifier, ':email' => $identifier]);
	$user = $statement->fetch();

	if ($user && password_verify($password, $user['password_hash'])) {
		session_regenerate_id(true);
		$_SESSION['user'] = [
			'id' => (int) $user['id'],
			'full_name' => $user['full_name'],
			'username' => $user['username'],
			'email' => $user['email'],
			'role' => $user['role'],
		];
		header('Location: ' . ($user['role'] === 'admin' ? 'admin/dashboard.php' : 'tenant/dashboard.php'));
		exit;
	} else {
		$error = 'Invalid email or password.';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dormitory Login</title>
	<link rel="stylesheet" href="css/login.css">
	<script src="js/app.js" defer></script>
</head>
<body>
	<main class="panel">
		<section class="login-side">
		<?php if (isset($_SESSION['username'])): ?>
			<h1>Welcome</h1>
			<p class="muted">You are logged in to the dormitory system.</p>
			<div class="alert success"><?= $message ?></div>
			<div class="role"><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></div>
			<a class="logout" href="logout.php">Log out</a>
		<?php else: ?>
			<h1>Hello again!</h1>
			<p class="muted">Sign in to continue to your dormitory account.</p>
			<?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
			<form method="post" action="index.php">
				<label for="identifier">Username or email</label>
			<input id="identifier" name="identifier" type="text" required autocomplete="username" placeholder="Username or email">
				<label for="password">Password</label>
				<div class="password-row"><input id="password" name="password" type="password" required autocomplete="current-password"><button class="password-toggle" type="button" data-toggle-password="password" aria-label="Show password">&#9673;</button></div>
				<a class="forgot" href="signup.php">Need an account? Sign up</a>
				<button type="submit">Sign in</button>
			</form>
		<?php endif; ?>
		</section>
		<aside class="art-side" aria-label="Sunset mountain landscape">
			<div class="mountain"></div>
			<div class="snow"></div>
			<div class="ground"></div>
			<div class="tree one"></div>
			<div class="tree two"></div>
			<div class="tree three"></div>
			<div class="tree four"></div>
			<div class="art-label">Dormitory / Welcome home</div>
		</aside>
	</main>
</body>
</html>
