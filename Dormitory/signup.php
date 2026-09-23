<?php
declare(strict_types=1);
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? null);
    $username = strtolower(trim((string) ($_POST['username'] ?? '')));
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!preg_match('/^[a-z0-9_]{3,40}$/', $username)) {
        $error = 'Username must be 3-40 characters using letters, numbers, or underscores.';
    } elseif (strlen($fullName) < 2) {
        $error = 'Please enter your full name.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must contain at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $statement = $pdo->prepare('SELECT username, email FROM users WHERE username = :username OR email = :email LIMIT 1');
        $statement->execute([':username' => $username, ':email' => $email]);
        $existingUser = $statement->fetch();
        if ($existingUser) {
            $error = strcasecmp($existingUser['username'], $username) === 0 ? 'That username is already registered.' : 'That email is already registered.';
        }
    }

    if (!$error) {
        try {
            $statement = $pdo->prepare('INSERT INTO users (username, full_name, email, password_hash, role) VALUES (:username, :full_name, :email, :password_hash, :role)');
            $statement->execute([':username' => $username, ':full_name' => $fullName, ':email' => $email, ':password_hash' => password_hash($password, PASSWORD_DEFAULT), ':role' => 'tenant']);
            $success = 'Account created. You can now log in.';
        } catch (PDOException $exception) {
            $error = 'The account could not be saved. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <main class="panel">
        <h1>Create account</h1>
        <p class="muted">Register for the dormitory system.</p>
        <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="post" action="signup.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" minlength="3" maxlength="40" pattern="[A-Za-z0-9_]{3,40}" required autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
            <label for="full_name">Full name</label>
            <input id="full_name" name="full_name" type="text" minlength="2" required value="<?= e($_POST['full_name'] ?? '') ?>">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" minlength="6" required autocomplete="new-password">
            <label for="confirm_password">Confirm password</label>
            <input id="confirm_password" name="confirm_password" type="password" minlength="6" required autocomplete="new-password">
            <button type="submit">Sign up</button>
        </form>
        <a class="link" href="index.php">Back to login</a>
    </main>
</body>
</html>
