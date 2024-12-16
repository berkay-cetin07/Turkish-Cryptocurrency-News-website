<?php
require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// Fetch today's data for Bitcoin
$coinId = BTC_COIN_ID;
$todayData = getTodayOhlcv($coinId);

$open = $high = $low = $close = null;
if (!empty($todayData) && isset($todayData[0])) {
    $open = $todayData[0]['open'];
    $high = $todayData[0]['high'];
    $low = $todayData[0]['low'];
    $close = $todayData[0]['close'];
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
    const data = [<?php echo $open; ?>, <?php echo $high; ?>, <?php echo $low; ?>, <?php echo $close; ?>];

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