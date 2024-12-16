<?php
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

/**
 * Fetch JSON data from a given URL using cURL.
 */
function fetchJsonData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpStatus !== 200) {
        return null; // or handle errors as needed
    }

    return json_decode($response, true);
}

/**
 * Get basic coin info from CoinPaprika
 * @param string $coinId For example 'btc-bitcoin', 'eth-ethereum', 'doge-dogecoin'
 */
function getCoinInfo($coinId) {
    $url = COINPAPRIKA_BASE_URL . '/' . urlencode($coinId);
    return fetchJsonData($url);
}

/**
 * Get market data for a specific coin
 * This returns an array of exchanges and prices for the given coin.
 * @param string $coinId
 */
function getCoinMarkets($coinId) {
    $url = COINPAPRIKA_BASE_URL . '/' . urlencode($coinId) . '/markets';
    return fetchJsonData($url);
}

/**
 * Helper function to get selected coin data (info + markets) for BTC, ETH, DOGE.
 * Returns an array keyed by coinId with 'info' and 'markets' keys.
 */
function getSelectedCoinsData() {
    $coins = [
        'Bitcoin' => BTC_COIN_ID,
        'Ethereum' => ETH_COIN_ID,
        'Dogecoin' => DOGE_COIN_ID
    ];

    $data = [];
    foreach ($coins as $coinName => $coinId) {
        $info = getCoinInfo($coinId);
        $markets = getCoinMarkets($coinId);
        
        $data[$coinName] = [
            'info' => $info,
            'markets' => $markets
        ];
    }

    return $data;
}

function getHistoricalPrices($coinId, $start, $end) {
    $url = COINPAPRIKA_BASE_URL . '/' . urlencode($coinId) . '/ohlcv/today';
    $data = fetchJsonData($url);

    $historicalData = [];
    if (!empty($data) && is_array($data)) {
        foreach ($data as $day) {
            $historicalData[] = [
                'timestamp' => $day['time_close'], 
                'close' => $day['close']
            ];
        }
    }
    var_dump($url);
    return $historicalData;
}

function getTodayOhlcv($coinId) {
    $url = COINPAPRIKA_BASE_URL . '/' . urlencode($coinId) . '/ohlcv/today';
    $data = fetchJsonData($url);
    return $data;
}