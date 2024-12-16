<div class="container">
    <h2>Bitcoin Haberleri</h2>
    <?php
        $newsItems = getCryptoNews(); 
        if (!empty($newsItems)): 
            foreach ($newsItems as $item):
    ?>
        <div class="news-item">
            <h3><a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">
                <?php echo htmlspecialchars($item['title']); ?>
            </a></h3>
            <p class="news-date" style="color: #999; font-size: 14px;">
                <?php echo date("d M Y, H:i", strtotime($item['pubDate'])); ?>
            </p>
            <p><?php echo htmlspecialchars($item['description']); ?></p>
        </div>
    <?php 
            endforeach; 
        else: 
    ?>
        <p>Şu an haber alınamadı. Lütfen daha sonra tekrar deneyin.</p>
    <?php endif; ?>
</div>
