<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}
?>

<section class="post-form">
    <h2 class="section-title">Oma profiili</h2>
    <p>Tervetuloa, <em><?= htmlspecialchars($_SESSION['username']) ?></em> profiiliisi!</p>
</section>