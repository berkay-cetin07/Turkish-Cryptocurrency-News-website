<?php
require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/includes/functions.php';

// Get the search query from the URL or set it as an empty string if not provided
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($q !== '') {
    // Fetch all news items from an external or internal source
    // `getCryptoNews` is assumed to return an array of news items
    $allNews = getCryptoNews(); // Example: [ 'title' => '', 'link' => '', 'description' => '' ]

    // Filter results by checking if the query matches the title or description (case-insensitive)
    foreach ($allNews as $item) {
        $titleMatch = stripos($item['title'], $q) !== false;
        $descMatch  = stripos($item['description'], $q) !== false;

        // If the search query is found in either the title or the description, add the item to results
        if ($titleMatch || $descMatch) {
            $results[] = $item;
        }
    }

    // --- Secure SQL Query to Prevent Injection ---
    try {
        // Establish a database connection using PDO with error handling enabled
        $conn = new PDO("mysql:host=db;dbname=crypto_news_db", "crypto_user", "crypto_pass");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Use a prepared statement to safely search for users with matching usernames
        $sql = "SELECT id, username FROM users WHERE username LIKE :query";
        $stmt = $conn->prepare($sql);

        // Bind the user-provided query to the prepared statement, ensuring safe execution
        $stmt->bindValue(':query', "%$q%", PDO::PARAM_STR);

        // Execute the query securely
        $stmt->execute();

        // If there are results, display them in a table format
        if ($stmt->rowCount() > 0) {
            echo "<div class='sql-injection-results'>";
            echo "<h3>Kullanıcı Bilgileri</h3>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Kullanıcı Adı</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Escape HTML characters to prevent XSS attacks
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        // Catch any database-related errors and display a sanitized error message
        echo "SQL Güvenlik Hatası: " . htmlspecialchars($e->getMessage());
    }
    // -------------------------------------------------------
}
?>

<div class="container">
    <h2>Arama Sonuçları</h2>
    <?php if ($q === ''): ?>
        <!-- Display a message if no query is provided -->
        <p>Lütfen bir arama terimi girin.</p>
    <?php else: ?>
        <!-- Display the search query -->
        <p><strong>Aranan:</strong> <?php echo htmlspecialchars($q); ?></p>
        <?php if (empty($results)): ?>
            <!-- Show a message if no results match the query -->
            <p>Aradığınız kriterlerde haber bulunamadı.</p>
        <?php else: ?>
            <!-- Display matching news items -->
            <?php foreach ($results as $item): ?>
                <div class="news-item">
                    <h3>
                        <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </a>
                    </h3>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
