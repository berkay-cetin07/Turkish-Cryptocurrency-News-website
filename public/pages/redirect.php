<?php
ob_start(); // Start output buffering 

include_once dirname(__DIR__, 2) . '/src/includes/functions.php';
include_once dirname(__DIR__, 2) . '/src/includes/header.php';
include_once dirname(__DIR__, 2) . '/src/includes/navbar.php';

// Redirect logic
if (isset($_GET['redirect'])) {
    $url = trim($_GET['redirect']); // Trim whitespace

    /*
    VULNERABILITY:
    This line takes user-provided input ($url) directly without strict validation beyond checking for a valid URL.
    While it uses `FILTER_VALIDATE_URL`, it does not restrict redirection to trusted domains, leading to
    CWE-601: URL Redirection to Untrusted Site ('Open Redirect'). 
    An attacker could exploit this by crafting malicious URLs to redirect users to phishing or malicious websites.
    */
    if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}"); // Redirect to the provided URL
        ob_end_flush(); // Send buffered output
        exit;
    } else {
        echo "<p style='color: red;'>Invalid URL provided!</p>"; // Error message for invalid URLs
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