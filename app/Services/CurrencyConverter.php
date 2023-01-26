<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    private $apiKey;
    // https://api.freecurrencyapi.com/v1/latest?apikey=YKEH0qgDQ88RuV8NzSquAGT0mA48MT4IsDJjGdZj&currencies=ILS&base_currency=BGN
    protected $baseUrl = 'https://api.freecurrencyapi.com/v1';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function convert(string $from, string $to, float $amount = 1): float
    {
        $response = Http::baseUrl($this->baseUrl)
            ->get('/latest', [
                'apikey' => $this->apiKey,
                'base_currency'=>$from,
                'currencies'=>$to,

            ]);

        $result = $response->json();
        return $result['data'][$to] * $amount;
    }
}
