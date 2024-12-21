<?php
require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// Get the search query from the URL
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($q !== '') {
    // Fetch all news items
    $allNews = getCryptoNews(); // This should return an array of [ 'title' => '', 'link' => '', 'description' => '' ]

    // Filter results: case-insensitive search in title or description
    foreach ($allNews as $item) {
        $titleMatch = stripos($item['title'], $q) !== false;
        $descMatch  = stripos($item['description'], $q) !== false;
        if ($titleMatch || $descMatch) {
            $results[] = $item;
        }
    }

    // --- SQLI  ---
    try {
        // PDO ile veritabanı bağlantısı
        $conn = new PDO("mysql:host=db;dbname=crypto_news_db", "crypto_user", "crypto_pass");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // **SQL Injection Zafiyeti** (Sömürülebilir)
        $sql = "SELECT * FROM users WHERE username LIKE '%$q%' OR password LIKE '%$q%'"; // Kullanıcı tablosuna erişim
        $stmt = $conn->query($sql);

        if ($stmt->rowCount() > 0) {
            echo "<div class='sql-injection-results'>";
            echo "<h3>Kullanıcı Bilgileri</h3>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Kullanıcı Adı</th><th>Şifre</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
        // Kullanıcı tablosundan veri döndürmeyen sorgular için artık hiçbir mesaj yazdırılmıyor.
    } catch (PDOException $e) {
        echo "SQL Injection Testi Hatası: " . htmlspecialchars($e->getMessage());
    }
    // -------------------------------------------------------
}
?>

<div class="container">
    <h2>Arama Sonuçları</h2>
    <?php if ($q === ''): ?>
        <p>Lütfen bir arama terimi girin.</p>
    <?php else: ?>
        <p><strong>Aranan:</strong> <?php echo htmlspecialchars($q); ?></p>
        <?php if (empty($results)): ?>
            <p>Aradığınız kriterlerde haber bulunamadı.</p>
        <?php else: ?>
            <?php foreach ($results as $item): ?>
                <div class="news-item">
                    <h3><a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">
                        <?php echo htmlspecialchars($item['title']); ?>
                    </a></h3>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
