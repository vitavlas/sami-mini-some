<?php

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'minisome';

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die('Yhteys tietokantaan epäonnistunut!' . mysqli_connect_error());
}

// Tests

$query = 'SELECT * FROM posts';
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $date = new DateTime($row['created_at']);
        $formatted_date = $date->format('d.m.Y \k\l\o H:i');

        echo "<h3>" . htmlspecialchars($row['author']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['content']) . "</p>";
        echo "<em>" . htmlspecialchars($formatted_date) . "</em>";
    }
} else {
    echo "Ei ole yhtä julkaisua.";
}