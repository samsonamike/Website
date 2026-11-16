<?php
// Edit these to match your MySQL setup.
const DB_HOST = 'localhost';
const DB_NAME = 'secure_site';
const DB_USER = 'root';
const DB_PASS = '';

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

function db(): PDO {
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function check_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Invalid form token. Go back and try again.');
    }
}

function require_login(): void {
    if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
}

function header_html(string $title, bool $loggedIn = false): void { ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> | Notebook</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="top">
  <a class="brand" href="index.php">Notebook</a>
  <button class="menu-btn" id="menuBtn" aria-label="Toggle menu" aria-expanded="false">Menu</button>
  <nav id="nav">
    <?php if ($loggedIn): ?>
      <a href="dashboard.php">My notes</a>
      <a href="logout.php">Log out</a>
    <?php else: ?>
      <a href="login.php">Log in</a>
      <a href="register.php">Create account</a>
    <?php endif; ?>
  </nav>
</header>
<main>
<?php }

function footer_html(): void { ?>
</main>
<script src="app.js"></script>
</body>
</html>
<?php }
