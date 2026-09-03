<?php
// formatted post time
$date = new DateTime($row['created_at']);
$formatted_date = $date->format('d.m.Y');
$formatted_time = $date->format('H:i');
?>

<article class="card">
    <div class="card-wrapper">
        <div class="card-avatar">
            <img src="https://placehold.co/64x64?text=ava" width="64" height="64" alt="User profile avatar">
        </div>
        <div class="card-section">
            <h3 class="card-title"><?= htmlspecialchars($row['author']) ?></h3>
            <p class="card-content"><?= htmlspecialchars($row['content']) ?></p>
            <div class="card-infobar">
                <div class="post-date">
                    <i class="fa-regular fa-calendar"></i>
                    <time datetime="<?= htmlspecialchars($formatted_date) ?>">
                        <?= htmlspecialchars($formatted_date) ?>
                    </time>
                </div>
                <div class="post-date">
                    <i class="fa-regular fa-clock"></i>
                    <time datetime="<?= htmlspecialchars($formatted_time) ?>">
                        <?= htmlspecialchars($formatted_time) ?>
                    </time>
                </div>
            </div>
        </div>
        <div class="card-actions">
            <a class="action-link" href="index.php?page=update-post&post-id=<?= htmlspecialchars($row['id']) ?>">
                <i class="fa-regular fa-pen-to-square"></i>
                 Muokkaa
            </a>
            <a class="action-link action-link--alert" href="index.php?page=delete-post&post-id=<?= htmlspecialchars($row['id']) ?>">
                <i class="fa-regular fa-trash-can"></i>
                 Poista
            </a>
        </div>
    </div>
</article>