<?php
require_once __DIR__ . '/../config/config.php';


function getCryptoNews() {
    $feedUrl = NEWS_FEED_URL;
    $rss = simplexml_load_file($feedUrl);

    $newsItems = [];
    if ($rss && isset($rss->channel->item)) {
        foreach ($rss->channel->item as $item) {
            $title = (string)$item->title;
            $link = (string)$item->link;
            $description = (string)$item->description;
            $pubDate = (string)$item->pubDate;

            $description = strip_tags($description);

            $newsItems[] = [
                'title'       => $title,
                'link'        => $link,
                'description' => $description,
                'pubDate'     => $pubDate
            ];
        }
    }
    return $newsItems;
}