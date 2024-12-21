<?php
require_once __DIR__ . '/../../src/utils/crypto_utils.php';

// Fetch today's data for Bitcoin using the new CryptoUtils class
$todayData = CryptoUtils::getTodayOhlcv('BTC');

// Initialize variables
$open = $high = $low = $close = null;

// Check if we have valid data
if ($todayData !== null) {
    $open = $todayData['open'];
    $high = $todayData['high'];
    $low = $todayData['low'];
    $close = $todayData['close'];
}
?>

<div class="container">
    <h2>Bitcoin Bugünkü (24 Saat) Değerleri</h2>
    <?php if ($open !== null && $high !== null && $low !== null && $close !== null): ?>
        <canvas id="todayChart" width="800" height="400"></canvas>
    <?php else: ?>
        <p>Bugün için veri alınamadı.</p>
    <?php endif; ?>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($open !== null && $high !== null && $low !== null && $close !== null): ?>
<script>
    const ctx = document.getElementById('todayChart');

    const labels = ['Open', 'High', 'Low', 'Close'];
    const data = [
        <?php echo number_format($open, 2); ?>, 
        <?php echo number_format($high, 2); ?>, 
        <?php echo number_format($low, 2); ?>, 
        <?php echo number_format($close, 2); ?>
    ];

    const todayChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'BTC Bugünkü Değerler (USD)',
                data: data,
                backgroundColor: [
                    'rgba(0, 99, 132, 0.6)',
                    'rgba(0, 155, 132, 0.6)',
                    'rgba(255, 205, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ],
                borderColor: [
                    'rgba(0, 99, 132, 1)',
                    'rgba(0, 155, 132, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Veri Tipi'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Fiyat (USD)'
                    },
                    beginAtZero: false
                }
            },
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: $${context.parsed.y.toLocaleString()}`;
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
</script>
<?php endif; ?>