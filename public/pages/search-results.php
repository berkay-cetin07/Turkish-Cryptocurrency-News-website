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
