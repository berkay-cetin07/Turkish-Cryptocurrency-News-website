<?php
require_once __DIR__ . '/../../src/config/config.php';

/**
 * Security Note: This is a basic URL blacklist implementation.
 * While it blocks common localhost references, it may be bypassed using:
 * - Octal/Hex representations (e.g., http://0177.0.0.1)
 * - Integer representation (e.g., http://2130706433)
 * - IPv6 notation (e.g., http://[::ffff:127.0.0.1])
 * - Partial IP resolution (e.g., http://127.1)
 * 
 */
function isUrlBlocked($url) {
    $blocked = array(
        '127.0.0.1',
        'localhost',
        '0.0.0.0',
        '::1'
    );

    foreach ($blocked as $blocked_url) {
        if (stripos($url, $blocked_url) !== false) {
            return true;
        }
    }
    return false;
}

$error = '';
$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_url'])) {
    $url = $_POST['api_url'];
    
    if (!isUrlBlocked($url)) {
        try {
            // Security Risk: file_get_contents() with user input can lead to SSRF
            // Allows an attacker to make requests from the server's perspective
            $response = file_get_contents($url);
            $result = $response;
        } catch (Exception $e) {
            $error = "API'ye erişilirken bir hata oluştu.";
        }
    } else {
        $error = "Bu URL'ye erişim engellendi!";
    }
}
?>

<div class="price-checker-container">
    <div class="checker-header">
        <h1>Coin Fiyat Kontrolü</h1>
        <p class="description">
            Farklı borsalardan kripto para fiyatlarını kontrol edin. 
            API endpoint URL'sini girerek fiyat verilerini çekebilirsiniz.
        </p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="checker-grid">
        <div class="input-section">
            <div class="api-form">
                <h2>API Sorgusu</h2>
                <form method="POST" action="/?page=price-checker">
                    <div class="form-group">
                        <label for="api_url">API Endpoint URL'si:</label>
                        <input type="url" 
                               id="api_url" 
                               name="api_url" 
                               placeholder="https://api.example.com/v1/prices/BTC"
                               required
                               class="api-input">
                    </div>
                    <div class="example-apis">
                        <h3>Örnek API'ler:</h3>
                        <ul>
                            <li>https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT</li>
                            <li>https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd</li>
                            <li>https://api.kraken.com/0/public/Ticker?pair=XBTUSDT</li>
                        </ul>
                    </div>
                    <button type="submit" class="submit-button">Fiyatları Getir</button>
                </form>
            </div>
        </div>

        <?php if ($result): ?>
        <div class="result-section">
            <h2>API Yanıtı</h2>
            <div class="result-container">
                <pre><?php echo htmlspecialchars($result); ?></pre>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.price-checker-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.checker-header {
    text-align: center;
    margin-bottom: 40px;
}

.checker-header h1 {
    color: #2563eb;
    margin-bottom: 15px;
}

.description {
    color: #4b5563;
    font-size: 1.1em;
    max-width: 800px;
    margin: 0 auto;
}

.checker-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
}

@media (min-width: 768px) {
    .checker-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.input-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
}

.api-form h2 {
    color: #1f2937;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #4b5563;
    font-weight: 500;
}

.api-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1em;
    transition: border-color 0.3s ease;
}

.api-input:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.example-apis {
    background: #f8fafc;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
}

.example-apis h3 {
    color: #4b5563;
    font-size: 0.9em;
    margin-bottom: 10px;
}

.example-apis ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.example-apis li {
    color: #6b7280;
    font-size: 0.85em;
    padding: 5px 0;
    word-break: break-all;
}

.submit-button {
    width: 100%;
    padding: 12px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.3s ease;
}

.submit-button:hover {
    background: #1d4ed8;
}

.result-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
}

.result-section h2 {
    color: #1f2937;
    margin-bottom: 20px;
}

.result-container {
    background: #f8fafc;
    padding: 15px;
    border-radius: 8px;
    overflow-x: auto;
}

.result-container pre {
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
    color: #4b5563;
    font-family: monospace;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    text-align: center;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
</style> 