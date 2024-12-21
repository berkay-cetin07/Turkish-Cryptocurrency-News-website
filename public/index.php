<?php
ob_start();
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/includes/functions.php';
require_once __DIR__ . '/../src/includes/session.php';

// Handle the `redirect.php` separately
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/redirect.php') !== false) {
    include __DIR__ . '/pages/redirect.php'; 
    exit;
}

// Default page routing logic
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$allowed_pages = ['home', 'news', 'coin-values', 'search-results', 'charts', 'support', 'exchange-status','redirect', 'login', 'logout', 'admin', 'price-checker'];


// Login sayfasına yönlendirme kontrolü
if (!isset($_SESSION['username']) && $page !== 'login') {
    header("Location: /?page=login");
    exit;
}

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

include __DIR__ . '/../src/includes/header.php';

// Yalnızca giriş yapmış kullanıcılar için navbar'ı göster
if (isset($_SESSION['username'])) {
    include __DIR__ . '/../src/includes/navbar.php';
}

// Sayfa yolunu kontrol edin
$file_path = __DIR__ . '/pages/' . $page . '.php';
if (file_exists($file_path)) {
    include $file_path;
} else {
    echo "<div style='text-align:center; color:red;'>Sayfa bulunamadı.</div>";
}

// Footer dahil edilir
include __DIR__ . '/../src/includes/footer.php';

ob_end_flush(); // Çıkışı tamponlayarak istemciye gönderir
?>
