<?php
require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// Fetch today's data for the three coins
$coins = [
    'Bitcoin' => BTC_COIN_ID,
    'Ethereum' => ETH_COIN_ID,
    'Dogecoin' => DOGE_COIN_ID
];

$todayData = [];
foreach ($coins as $coinName => $coinId) {
    $data = getTodayOhlcv($coinId);
    if (!empty($data) && isset($data[0])) {
        $todayData[$coinName] = [
            'open' => $data[0]['open'],
            'high' => $data[0]['high'],
            'low'  => $data[0]['low'],
            'close'=> $data[0]['close']
        ];
    } else {
        $todayData[$coinName] = null;
    }
}
?>

<div class="container">
    <h1 style="text-align:center; margin-bottom:40px;">Güncel Kripto Durumu</h1>
    <p style="text-align:center; margin-bottom:40px;">
        Better Be Rich, Türkiyenin En Büyük Kripto Haber Platformu ile bugünün en son değerlerini Bitcoin, Ethereum ve Dogecoin için görüntüleyin.
    </p>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(350px,1fr)); gap:30px; margin-bottom:40px;">
        <?php foreach ($todayData as $coinName => $data): ?>
            <div class="coin-chart-card">
                <h2 style="text-align:center;"><?php echo htmlspecialchars($coinName); ?></h2>
                <?php if ($data): ?>
                    <canvas id="<?php echo strtolower($coinName); ?>Chart"></canvas>
                <?php else: ?>
                    <p style="text-align:center;">Veri mevcut değil.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 style="text-align:center; margin-bottom:20px;">Kapanış Fiyat Karşılaştırması</h2>
    <p style="text-align:center; margin-bottom:40px;">
        Bitcoin, Ethereum ve Dogecoin'in günlük kapanış fiyatlarını karşılaştırın.
    </p>
    <div style="max-width:600px; margin:0 auto;">
        <canvas id="comparisonChart"></canvas>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
<?php 
// Prepare JS data for individual charts
foreach ($todayData as $coinName => $data) {
    if ($data) {
        $varName = strtolower($coinName) . 'Data';
        echo "const $varName = " . json_encode($data, JSON_HEX_TAG) . ";\n";
    }
}
?>

// Individual Charts Setup
<?php foreach ($todayData as $coinName => $data): 
    if ($data):
        $canvasId = strtolower($coinName) . 'Chart';
?>
const ctx_<?php echo strtolower($coinName); ?> = document.getElementById('<?php echo $canvasId; ?>');

const <?php echo strtolower($coinName); ?>Chart = new Chart(ctx_<?php echo strtolower($coinName); ?>, {
    type: 'bar',
    data: {
        labels: ['Open', 'High', 'Low', 'Close'],
        datasets: [{
            label: '<?php echo htmlspecialchars($coinName); ?> Bugünkü Değerler (USD)',
            data: [<?php echo $data['open']; ?>, <?php echo $data['high']; ?>, <?php echo $data['low']; ?>, <?php echo $data['close']; ?>],
            backgroundColor: [
                'rgba(0,99,132,0.6)',
                'rgba(0,155,132,0.6)',
                'rgba(255,205,86,0.6)',
                'rgba(75,192,192,0.6)'
            ],
            borderColor: [
                'rgba(0,99,132,1)',
                'rgba(0,155,132,1)',
                'rgba(255,205,86,1)',
                'rgba(75,192,192,1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: { display: true, text: 'Veri Tipi' }
            },
            y: {
                title: { display: true, text: 'Fiyat (USD)' },
                beginAtZero: false
            }
        },
        plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false }
    }
});
<?php endif; endforeach; ?>

// Comparison Chart (Based on Close Price)
<?php 
$comparisonLabels = [];
$comparisonData = [];
$comparisonColors = ['rgba(0,111,230,0.6)', 'rgba(50,205,50,0.6)', 'rgba(255,160,0,0.6)'];
$comparisonBorderColors = ['rgba(0,111,230,1)', 'rgba(50,205,50,1)', 'rgba(255,160,0,1)'];

$i = 0;
foreach ($todayData as $coinName => $data) {
    $comparisonLabels[] = $coinName;
    $comparisonData[] = $data ? $data['close'] : null;
    $i++;
}
?>

const ctx_comparison = document.getElementById('comparisonChart');
const comparisonChart = new Chart(ctx_comparison, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($comparisonLabels, JSON_HEX_TAG); ?>,
        datasets: [{
            label: 'Günlük Kapanış Fiyatları (USD)',
            data: <?php echo json_encode($comparisonData, JSON_HEX_TAG); ?>,
            backgroundColor: ['rgba(0,111,230,0.6)', 'rgba(50,205,50,0.6)', 'rgba(255,160,0,0.6)'],
            borderColor: ['rgba(0,111,230,1)', 'rgba(50,205,50,1)', 'rgba(255,160,0,1)'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                title: { display: true, text: 'Coinler' }
            },
            y: {
                title: { display: true, text: 'Fiyat (USD)' },
                beginAtZero: false
            }
        },
        plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false }
    }
});
</script>
