<?php

/* ==== CONFIG ===== */

define('BASE_PATH', __DIR__);

$page = $_GET['page'] ?? 'home';

// routes
require_once BASE_PATH . '/config/routes.php';

// helpers
require_once BASE_PATH . '/config/helpers.php';

// db connection
require_once BASE_PATH . '/config/database.php';


/* ==== APP LAYOUT ===== */

include_once BASE_PATH . '/includes/header.php';

// content
if (array_key_exists($page, $routes)) {
    include_once $routes[$page];
} else {
    http_response_code(404);
    echo "Haluamaasi sivua ei löytynyt.";
}

include_once BASE_PATH . '/includes/footer.php';
