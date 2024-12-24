<?php
ob_start(); // Start output buffering

include_once dirname(__DIR__, 2) . '/src/includes/functions.php';
include_once dirname(__DIR__, 2) . '/src/includes/header.php'; 
include_once dirname(__DIR__, 2) . '/src/includes/navbar.php';

// Patched Redirect logic
if (isset($_GET['redirect'])) {
    $url = trim($_GET['redirect']); // Trim whitespace
    $allowedDomains = ['binance.com']; // Patch: Define allowed domains

    // Parse the host from the URL
    $parsedUrl = parse_url($url);
    $host = $parsedUrl['host'] ?? '';
    $scheme = $parsedUrl['scheme'] ?? '';
    // Patch: Validate that the host is in the list of allowed domains and check if its https as well (more secure)
    if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) && in_array($host, $allowedDomains) && $scheme === 'https') {
        header("Location: {$url}"); // Redirect to the trusted URL
        ob_end_flush(); // Send buffered output
        exit;
    } else {
        echo "<p style='color: red;'>Yetkisiz yönlendirme girişimi tespit edildi. URL güvenilir değil!</p>";
    }
}
?>

<div class="container">
    <h2 style="text-align:center; margin-bottom:30px;">Ziyaret ettiğiniz için teşekkür ederiz! Kripto Para Birimleri Hakkında Daha Fazlasını Keşfedin</h2>
    <p style="text-align:center; margin-bottom:30px;">
        <!-- Example link demonstrating the redirection feature -->
        Kripto paralarla ilgili daha ayrıntılı analizler ve güncel haberler için <a href="redirect.php?redirect=https://binance.com" class="btn btn-primary" style="margin:0;">iş ortaklarımızı ziyaret edin</a>.
    </p>
</div>

<?php
ob_end_flush(); // Send buffered output
?>