<?php
// formatted post time
$date = new DateTime($row['created_at']);
$formatted_date = $date->format('d.m.Y \k\l\o H:i');
?>

<article class="card">
    <div class="card-wrapper">
        <div class="card-avatar">
            <img src="" alt="User profile avatar">
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
                    <time datetime="<?= htmlspecialchars($formatted_date) ?>">
                        <?= htmlspecialchars($formatted_date) ?>
                    </time>
                </div>
            </div>
        </div>
    </div>
</article>