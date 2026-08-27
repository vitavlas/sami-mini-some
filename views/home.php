<?php

$query = 'SELECT * FROM posts';
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        include BASE_PATH . '/includes/card.php';
    }
} else {
    echo "Ei ole yhtä julkaisua.";
}