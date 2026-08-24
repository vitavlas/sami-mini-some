<?php

$server = 'localhost';
$username = 'root';
$password = '';

$conn = mysqli_connect($server, $username, $password);

if (!$conn) {
    die('Yhteys tietokantaan epäonnistunut!' . mysqli_connect_error());
}