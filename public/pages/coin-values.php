<?php
require_once __DIR__ . '/../../src/utils/crypto_utils.php';

$coinsData = CryptoUtils::getSelectedCoinsData();
?>

<div class="container">
    <h2>Cryptocurrency Prices</h2>
    <div class="coin-grid">
        <?php foreach ($coinsData as $coinName => $data): ?>
            <div class="coin-card">
                <h3><?php echo htmlspecialchars($coinName); ?></h3>
                <div class="coin-details">
                    <p class="price">
                        <span class="label">Price:</span>
                        <span class="value">$<?php echo number_format($data['info']['price_usd'], 2); ?></span>
                    </p>
                    <p class="change <?php echo $data['info']['percent_change_24h'] >= 0 ? 'positive' : 'negative'; ?>">
                        <span class="label">24h Change:</span>
                        <span class="value"><?php echo number_format($data['info']['percent_change_24h'], 2); ?>%</span>
                    </p>
                    <p class="volume">
                        <span class="label">24h Volume:</span>
                        <span class="value">$<?php echo number_format($data['info']['volume_24h_usd'], 0); ?></span>
                    </p>
                    <p class="updated">
                        <span class="label">Updated:</span>
                        <span class="value"><?php echo $data['info']['last_updated']; ?></span>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.coin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}

.coin-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.coin-card h3 {
    margin: 0 0 15px 0;
    color: #2563eb;
}

.coin-details p {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
    padding: 5px 0;
    border-bottom: 1px solid #f3f4f6;
}

.coin-details p:last-child {
    border-bottom: none;
}

.label {
    color: #6b7280;
    font-weight: 500;
}

.value {
    font-weight: 600;
}

.positive {
    color: #10b981;
}

.negative {
    color: #ef4444;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    color: #1f2937;
    margin-bottom: 20px;
    padding-left: 20px;
}
</style>
