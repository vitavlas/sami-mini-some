<?php
$query = "
    SELECT
        posts.id,
        posts.content,
        posts.user_id,
        posts.created_at,
        users.username AS author
    FROM posts
    INNER JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
";

$result = mysqli_query($conn, $query);
?>

<div class="card-list">
    <h2 class="section-title">Viimeisimmät Julkaisut</h2>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            include BASE_PATH . '/includes/card.php';
        }
    } else {
        echo "<p>Ei ole yhtä julkaisua.</p>";
    }
    ?>

</div>