<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// Define the whitelist
$whitelist = [
    'coindesk.com' // Only allow URLs from this domain
];

// Function to check if a URL is whitelisted
function isWhitelisted($url, $whitelist) {
    $parsedUrl = parse_url($url); // Parse the URL to extract its components
    if (!$parsedUrl || !isset($parsedUrl['host'])) {
        return false; // Return false if the URL is invalid or lacks a host component
    }

    $host = $parsedUrl['host']; // Get the host of the URL

    // Check if the host matches or is a subdomain of any whitelisted domain
    foreach ($whitelist as $allowed) {
        if ($host === $allowed || substr($host, -strlen(".$allowed")) === ".$allowed") {
            return true; // Return true if the host matches the whitelist
        }
    }

    return false;
}

$error = '';
$response = '';
$chartData = [];

// Handle API URL submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api_url'])) {
    $api_url = trim($_POST['api_url']); // Remove unnecessary spaces from the URL

    // Check if the URL is whitelisted
    if (!isWhitelisted($api_url, $whitelist)) {
        $error = "API isteği başarısız."; // General error message for all non-whitelisted URLs
    } else {
        // Attempt to fetch data from the API
        try {
            $options = [
                'http' => [
                    'method' => 'GET', // Use a GET request
                    'header' => 'User-Agent: CustomClient/1.0' // Add a custom user-agent
                ]
            ];
            $context = stream_context_create($options); // Create a stream context for the request
            $response = @file_get_contents($api_url, false, $context); // Make the API call

            if ($response === false) {
                throw new Exception("API isteği başarısız oldu."); // Throw an exception if the request fails
            }

            $chartData = json_decode($response, true); // Decode the JSON response into an associative array
        } catch (Exception $e) {
            $error = "API isteği başarısız."; // Display a general error message if the request fails
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

        <div class="guide">
            <h3>Örnek Kullanım:</h3>
            <p><strong>1. API URL'si:</strong> https://api.coindesk.com/v1/bpi/historical/close.json</p>
            <p>Yukarıdaki URL'i örnek bir kullanım için kullanabilir, kripto fiyatlarını getirebilirsiniz.</p>
        </div>
    </div>
</body>
</html>
