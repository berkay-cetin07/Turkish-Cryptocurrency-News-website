<?php
// Include shared components
include_once dirname(__DIR__, 2) . '/src/includes/functions.php';
include_once dirname(__DIR__, 2) . '/src/includes/header.php';
include_once dirname(__DIR__, 2) . '/src/includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['exchange_domain'])) {
    $domain = $_POST['exchange_domain']; // User input for domain or IP
    /*
    VULNERABILITY:
    This line directly includes user-provided input ($domain) into the shell command 
    without proper sanitization or validation, leading to Command Injection vulnerability (CWE-78).
    An attacker can inject malicious commands (e.g., "; rm -rf /") which will be executed on the server.
    */
    $output = shell_exec("ping -c 4 {$domain}"); 
}
?>

<div class="container">
    <!-- Centralized container for form and results -->
    <h2 style="text-align:center; margin-bottom:30px;">Kripto Para Borsası Sunucu Bağlantı Testi</h2>
    <p style="text-align:center; margin-bottom:30px;">
        En sevdiğiniz kripto para borsasının çevrimiçi olup olmadığını öğrenmek için aşağıya alan adını veya IP adresini girin.
    </p>

    <!-- Input form to collect domain or IP address from user -->
    <form method="post" class="form-test" style="max-width: 600px; margin: 0 auto;">
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="exchange_domain" class="form-label" style="display: block; font-weight: bold; margin-bottom: 10px;">Borsa Domain Adı veya IP Adresi Girin:</label>
            <input type="text" id="exchange_domain" name="exchange_domain" class="form-input" placeholder="örn: binance.com" required style="width: 100%; padding: 10px; border: 1px solid #e5e5e5; border-radius: 4px;">
        </div>
        <div class="form-group" style="text-align:center; margin-top:20px;">
            <button type="submit" class="btn btn-primary">Bağlantıyı Test Et</button>
        </div>
    </form>

    <?php if (isset($output)): ?>
        <!-- Displaying the output of the ping command if $output is set -->
        <div class="output-section" style="margin-top: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <h3 style="text-align: center; margin-bottom: 20px;">Bağlantı Testi Sonucu</h3>
            <div class="output-container" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 20px; font-family: monospace; white-space: pre-wrap; word-wrap: break-word;">
                <?php echo htmlspecialchars($output); ?>
            </div>
        </div>
    <?php endif; ?>
</div>