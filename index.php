<?php
require 'config.php';
header_html('Home', !empty($_SESSION['user_id']));
?>
<section class="hero">
  <h1>Your notes, kept private.</h1>
  <p>Create an account, write notes, and read them back from any device. Each note is visible only to you.</p>
  <?php if (empty($_SESSION['user_id'])): ?>
    <a class="btn" href="register.php">Create account</a>
  <?php else: ?>
    <a class="btn" href="dashboard.php">Open my notes</a>
  <?php endif; ?>
</section>
<?php footer_html();
