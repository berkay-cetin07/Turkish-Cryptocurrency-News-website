<?php
ob_start(); // Start output buffering----vulnerable CWE-601: URL Redirection to Untrusted Site ('Open Redirect')

include_once dirname(__DIR__, 2) . '/src/includes/functions.php';
include_once dirname(__DIR__, 2) . '/src/includes/header.php'; // Includes HTML output
include_once dirname(__DIR__, 2) . '/src/includes/navbar.php';

// Redirect logic
if (isset($_GET['redirect'])) {
    $url = trim($_GET['redirect']); // Trim whitespace

    // Vulnerable: No strict validation, allowing open redirection
    if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}"); // Redirect to the provided URL
        ob_end_flush(); // Send buffered output
        exit;
    } else {
        echo "<p style='color: red;'>Invalid URL provided!</p>";
    }
}

?>

<div class="container">
    <h2 style="text-align:center; margin-bottom:30px;">Ziyaret ettiğiniz için teşekkür ederiz! Kripto Para Birimleri Hakkında Daha Fazlasını Keşfedin</h2>
    <p style="text-align:center; margin-bottom:30px;">
        Kripto paralarla ilgili daha ayrıntılı analizler ve güncel haberler için <a href="redirect.php?redirect=http://example.com" class="btn btn-primary" style="margin:0;">iş ortaklarımızı ziyaret edin</a>.
    </p>
</div>

<?php
ob_end_flush(); // Send buffered output
?>