<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ? OR email = ?');
    $login = trim($_POST['login'] ?? '');
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Wrong username/email or password.';
}
header_html('Log in');
?>
<form class="card" method="post">
  <h1>Log in</h1>
  <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <label>Username or email <input name="login" required autocomplete="username"></label>
  <label>Password <input type="password" name="password" required autocomplete="current-password"></label>
  <button class="btn" type="submit">Log in</button>
  <p class="alt">No account yet? <a href="register.php">Create one</a></p>
</form>
<?php footer_html();
