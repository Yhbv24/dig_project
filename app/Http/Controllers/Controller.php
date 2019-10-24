<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
class Controller extends BaseController
{
    /**
     * Redis client
     *
     * @var Client
     */
    protected $client;
    
    /**
     * Class constructor
     * 
     * @return void
     */
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
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
        } catch (\Exception $ex) {
            return json_encode(['message' => $ex->getMessage()]);
        }

        return $response;
    }
}
