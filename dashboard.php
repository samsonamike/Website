<?php
require 'config.php';
require_login();
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (($_POST['action'] ?? '') === 'add') {
        $title = trim($_POST['title'] ?? '');
        $body  = trim($_POST['body'] ?? '');
        if ($title !== '' && $body !== '' && mb_strlen($title) <= 100) {
            db()->prepare('INSERT INTO notes (user_id, title, body) VALUES (?, ?, ?)')->execute([$uid, $title, $body]);
        }
    } elseif (($_POST['action'] ?? '') === 'delete') {
        // user_id in the WHERE clause stops users deleting each other's notes
        db()->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?')->execute([(int) $_POST['id'], $uid]);
    }
    header('Location: dashboard.php'); exit;
}

$stmt = db()->prepare('SELECT id, title, body, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$uid]);
$notes = $stmt->fetchAll();

header_html('My notes', true);
?>
<h1>Hello, <?= e($_SESSION['username']) ?></h1>

<form class="card" method="post">
  <h2>New note</h2>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="action" value="add">
  <label>Title <input name="title" maxlength="100" required></label>
  <label>Note <textarea name="body" id="body" rows="4" maxlength="2000" required></textarea></label>
  <small id="count">0 / 2000</small>
  <button class="btn" type="submit">Save note</button>
</form>

<section class="notes">
  <?php if (!$notes): ?>
    <p class="empty">No notes yet. Write your first one above.</p>
  <?php endif; ?>
  <?php foreach ($notes as $n): ?>
    <article class="note">
      <h3><?= e($n['title']) ?></h3>
      <p><?= nl2br(e($n['body'])) ?></p>
      <footer>
        <time><?= e($n['created_at']) ?></time>
        <form method="post" class="del">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= (int) $n['id'] ?>">
          <button type="submit" class="link">Delete</button>
        </form>
      </footer>
    </article>
  <?php endforeach; ?>
</section>
<?php footer_html();
