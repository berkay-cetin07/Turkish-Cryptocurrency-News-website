<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/includes/functions.php';

// Handle the `redirect.php` separately
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/redirect.php') !== false) {
    include __DIR__ . '/pages/redirect.php'; 
    exit;
}

// Default page routing logic
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Define allowed pages for routing
$allowed_pages = ['home', 'news', 'coin-values', 'search-results', 'charts', 'upload', 'exchange-status','redirect'];

// If the requested page is not allowed, default to 'home'
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Include the required files for the page
include __DIR__ . '/../src/includes/header.php';
include __DIR__ . '/../src/includes/navbar.php';
include __DIR__ . '/pages/' . $page . '.php';
include __DIR__ . '/../src/includes/footer.php';
