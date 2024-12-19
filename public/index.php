<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/includes/functions.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$allowed_pages = ['home', 'news', 'coin-values', 'search-results', 'charts', 'upload'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

include __DIR__ . '/../src/includes/header.php';
include __DIR__ . '/../src/includes/navbar.php';
include __DIR__ . '/pages/' . $page . '.php';
include __DIR__ . '/../src/includes/footer.php';
