<?php
$_SESSION = [];
session_destroy();

header('Location: index.php?page=home');
exit;
