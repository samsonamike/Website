<?php
require 'config.php';
$errors = [];
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{3,40}$/', $username)) $errors[] = 'Username must be 3-40 letters, numbers, or underscores.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';

    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'That username or email is already registered.';
        } else {
            $ins = db()->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $ins->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) db()->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: dashboard.php'); exit;
        }
    }
}
header_html('Create account');
?>
<form class="card" method="post" novalidate>
  <h1>Create account</h1>
  <?php foreach ($errors as $err): ?><p class="error"><?= e($err) ?></p><?php endforeach; ?>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label>Username <input name="username" value="<?= e($username) ?>" required autocomplete="username"></label>
  <label>Email <input type="email" name="email" value="<?= e($email) ?>" required autocomplete="email"></label>
  <label>Password <input type="password" name="password" required minlength="8" autocomplete="new-password"></label>
  <button class="btn" type="submit">Create account</button>
  <p class="alt">Already registered? <a href="login.php">Log in</a></p>
</form>
<?php footer_html();
