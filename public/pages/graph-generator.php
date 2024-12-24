<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started already
}

require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// Blacklist for restricted URLs
$blacklist = [
    '127.0.0.1',   // Localhost IP
    '0.0.0.0',     // Invalid or placeholder IP
             
];

// Function to check if the given URL is blacklisted
function isBlacklisted($url, $blacklist) {
    $parsedUrl = parse_url($url); // Parse the URL to extract components like host
    if (!$parsedUrl || !isset($parsedUrl['host'])) {
        return false; // If the URL is invalid or doesn't have a host, skip blacklist check
    }

    $host = strtolower($parsedUrl['host']);

    foreach ($blacklist as $blocked) {
        // Only block exact matches; allow subdomains or encoded forms to bypass
        if ($host === $blocked) {
            return true; // Exact match blocked
        }
    }
    return false; // The URL is not blacklisted
}

$error = '';
$response = '';
$chartData = [];

// Handling the submitted API URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_url'])) {
    $api_url = trim($_POST['api_url']); // Trim spaces from the user-provided URL

    // Blacklist validation
    if (isBlacklisted($api_url, $blacklist)) {
        $error = "Bu URL kara listeye alınmıştır. Erişim engellendi: " . htmlspecialchars($api_url); // Notify user about restricted URL
    } else {
        // Attempt to fetch the API data
        try {
            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: CustomClient/1.0' // Add a custom user-agent header for the request
                ]
            ];
            $context = stream_context_create($options); // Create a context for the HTTP request
            $response = @file_get_contents($api_url, false, $context); // Fetch the data from the API

            // The request now allows user-provided URLs without sufficient validation.
            // This can be exploited to perform SSRF (Server-Side Request Forgery), allowing attackers
            // to access internal resources like `http://127.0.0.1` or even cloud metadata services.

            if ($response === false) {
                throw new Exception("API isteği başarısız oldu."); // Handle failed API requests gracefully
            }

            $chartData = json_decode($response, true); // Decode the JSON response to process it for the chart
        } catch (Exception $e) {
            $error = "API isteği başarısız: " . htmlspecialchars($e->getMessage()); // Handle errors during the request
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kripto Para Grafik Oluşturucu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            border-radius: 10px;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #2563eb;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 12px 20px;
            background-color: #2563eb;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #5691c8;
        }

        .error {
            color: red;
            margin-top: 20px;
        }

        .guide {
            margin-top: 20px;
            background-color: #eaf4ff;
            color: #2563eb;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            text-align: left;
        }

        .guide p {
            margin: 5px 0;
        }

        .guide strong {
            color: #333;
        }

        canvas {
            margin-top: 30px;
            max-width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Kripto Para Grafik Oluşturucu</h2>
        <form method="POST">
            <input type="text" name="api_url" placeholder="API URL'si girin" required>
            <button type="submit">Grafik Oluştur</button>
        </form>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <?php if ($chartData && isset($chartData['bpi'])): ?>
            <canvas id="cryptoChart"></canvas>
            <script>
                const ctx = document.getElementById('cryptoChart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(<?= json_encode($chartData['bpi']) ?>),
                        datasets: [{
                            label: 'Bitcoin Fiyatı',
                            data: Object.values(<?= json_encode($chartData['bpi']) ?>),
                            borderColor: '#2563eb',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tarih'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Fiyat (USD)'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>
        
        <!-- User Guide -->
        <div class="guide">
            <h3>Örnek Kullanım:</h3>
            <p><strong>1. API URL'si:</strong> https://api.coindesk.com/v1/bpi/historical/close.json</p>
            <p>Yukarıdaki URL'i örnek bir kullanım için kullanabilir, kripto fiyatlarını getirebilirsiniz.</p>
        </div>
    </div>
</body>
</html>
