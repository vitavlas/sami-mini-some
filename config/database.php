<?php

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'minisome';

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die('Yhteys tietokantaan epäonnistunut!' . mysqli_connect_error());
}
