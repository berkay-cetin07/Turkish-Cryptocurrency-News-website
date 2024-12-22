<?php
class CryptoUtils {
    private static $api_base = 'https://api.binance.com/api/v3';
    
    public static function debugLog($message) {
        echo "<span style='color: #212529;'>$message</span>";
    }

    private static function makeRequest($endpoint) {
        $ch = curl_init(self::$api_base . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("CURL Error: " . $error);
        }
        
        return json_decode($response, true);
    }

    public static function getSelectedCoinsData() {
        try {
            $symbols = ['BTCUSDT', 'ETHUSDT', 'DOGEUSDT'];
            $results = [];

            foreach ($symbols as $symbol) {
                try {
                    // Get 24hr ticker price change statistics
                    $ticker = self::makeRequest("/ticker/24hr?symbol=$symbol");
                    
                    $coinName = str_replace('USDT', '', $symbol);
                    $results[$coinName] = [
                        'info' => [
                            'name' => $coinName,
                            'symbol' => $symbol,
                            'price_usd' => floatval($ticker['lastPrice']),
                            'market_cap_usd' => null,
                            'volume_24h_usd' => floatval($ticker['quoteVolume']),
                            'percent_change_24h' => floatval($ticker['priceChangePercent']),
                            'last_updated' => date('Y-m-d H:i:s', floor($ticker['closeTime'] / 1000))
                        ],
                        'markets' => []
                    ];
                } catch (Exception $e) {
                    self::debugLog("Error fetching $symbol: " . $e->getMessage());
                }
            }
            return $results;
        } catch (Exception $e) {
            self::debugLog("Exchange error: " . $e->getMessage());
            return [];
        }
    }

    public static function getTodayOhlcv($symbol) {
        try {
            $symbol = strtoupper($symbol) . 'USDT';
            $interval = '1d';
            $limit = 1;
            
            // Get Kline/Candlestick data
            $klines = self::makeRequest("/klines?symbol=$symbol&interval=$interval&limit=$limit");
            
            if (!empty($klines)) {
                $data = $klines[0];
                return [
                    'time_open' => $data[0],
                    'open' => floatval($data[1]),
                    'high' => floatval($data[2]),
                    'low' => floatval($data[3]),
                    'close' => floatval($data[4]),
                    'volume' => floatval($data[5])
                ];
            }
            return null;
        } catch (Exception $e) {
            self::debugLog("OHLCV error: " . $e->getMessage());
            return null;
        }
    }
}