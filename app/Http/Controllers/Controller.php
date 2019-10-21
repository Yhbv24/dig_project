<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libs\Request;

class Controller extends BaseController
{
    protected $client;
    
    protected function __construct()
    {
        $this->client = new \Predis\Client();
    }

    /**
     * Fetches data from a given URL
     *
     * @return string
     */
    protected function fetchData(string $url): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
