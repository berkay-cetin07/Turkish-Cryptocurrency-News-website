<div class="container">
    <h2 style="text-align:center; margin-bottom:40px;">Kripto Para Değerleri</h2>

    <?php
        $coinsData = getSelectedCoinsData();
        
        echo '<div class="coin-cards">';
        foreach ($coinsData as $coinName => $coinData):
            $info = $coinData['info'];
            $markets = $coinData['markets'];

            // Extract info
            $symbol = $info['symbol'] ?? '';
            $description = $info['description'] ?? '';
            $rank = $info['rank'] ?? '';
            $logo = $info['logo'] ?? '';

            // Truncate description for a cleaner look
            $shortDescription = (strlen($description) > 200) ? substr($description, 0, 200) . '...' : $description;
    ?>
        <div class="coin-card">
            <div class="coin-header">
                <?php if($logo): ?>
                    <img src="<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($coinName); ?> Logo" class="coin-logo">
                <?php endif; ?>
                <div class="coin-title">
                    <h3><?php echo htmlspecialchars($coinName) . " (" . htmlspecialchars($symbol) . ")"; ?></h3>
                    <span class="coin-rank">Sıra: #<?php echo htmlspecialchars($rank); ?></span>
                </div>
            </div>
            <p class="coin-description"><?php echo nl2br(htmlspecialchars($shortDescription)); ?></p>

            <?php if (!empty($markets)): ?>
                <h4 class="market-title">Piyasalar (İlk 3):</h4>
                <table class="market-table">
                    <thead>
                        <tr>
                            <th>Borsa</th>
                            <th>Çift</th>
                            <th>Fiyat (USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < min(3, count($markets)); $i++): 
                            $market = $markets[$i];
                            $exchange = $market['exchange_name'] ?? '';
                            $pair = $market['pair'] ?? '';
                            $priceUsd = isset($market['quotes']['USD']['price']) ? $market['quotes']['USD']['price'] : null;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($exchange); ?></td>
                            <td><?php echo htmlspecialchars($pair); ?></td>
                            <td><?php echo $priceUsd !== null ? number_format($priceUsd, 4) : 'N/A'; ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Bu coin için piyasa bilgisi alınamadı.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
</div>
