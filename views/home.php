<?php
$query = 'SELECT * FROM posts';
$result = mysqli_query($conn, $query);
?>

<div class="card-list">

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