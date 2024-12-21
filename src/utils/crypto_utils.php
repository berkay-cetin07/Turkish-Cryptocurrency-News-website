<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use ccxt\Exchange;
use ccxt\binance;

class CryptoUtils {
    private static $exchange = null;
    
    private static function getExchange() {
        if (self::$exchange === null) {
            self::$exchange = new binance([
                'enableRateLimit' => true,
                'timeout' => 30000,
            ]);
        }
        return self::$exchange;
    }

    public static function debugLog($message) {
        echo "<span style='color: #212529;'>$message</span>";
    }

    public static function getSelectedCoinsData() {
        try {
            $exchange = self::getExchange();
            $symbols = ['BTC/USDT', 'ETH/USDT', 'DOGE/USDT'];
            $results = [];

            foreach ($symbols as $symbol) {
                try {
                    $ticker = $exchange->fetch_ticker($symbol);
                    $timestamp = is_float($ticker['timestamp']) 
                        ? (int)round($ticker['timestamp']) 
                        : $ticker['timestamp'];
                        
                    $results[str_replace('/USDT', '', $symbol)] = [
                        'info' => [
                            'name' => str_replace('/USDT', '', $symbol),
                            'symbol' => $symbol,
                            'price_usd' => $ticker['last'],
                            'market_cap_usd' => null, // Binance doesn't provide this
                            'volume_24h_usd' => $ticker['quoteVolume'],
                            'percent_change_24h' => $ticker['percentage'],
                            'last_updated' => date('Y-m-d H:i:s', (int)($timestamp / 1000))
                        ],
                        'markets' => []
                    ];
                } catch (\Exception $e) {
                    self::debugLog("Error fetching $symbol: " . $e->getMessage());
                }
            }
            return $results;
        } catch (\Exception $e) {
            self::debugLog("Exchange error: " . $e->getMessage());
            return [];
        }
    }

    public static function getTodayOhlcv($symbol) {
        try {
            $exchange = self::getExchange();
            $timeframe = '1d';
            $symbol = strtoupper($symbol) . '/USDT';
            
            $ohlcv = $exchange->fetch_ohlcv($symbol, $timeframe, null, 1);
            
            if (!empty($ohlcv)) {
                $data = $ohlcv[0];
                $timestamp = is_float($data[0]) 
                    ? (int)round($data[0]) 
                    : $data[0];
                    
                return [
                    'time_open' => $timestamp,
                    'open' => $data[1],
                    'high' => $data[2],
                    'low' => $data[3],
                    'close' => $data[4],
                    'volume' => $data[5]
                ];
            }
            return null;
        } catch (\Exception $e) {
            self::debugLog("OHLCV error: " . $e->getMessage());
            return null;
        }
    }

    private static function formatTimestamp($timestamp) {
        if (is_float($timestamp)) {
            return (int)round($timestamp);
        }
        return $timestamp;
    }
}