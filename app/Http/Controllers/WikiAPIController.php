<?php

namespace App\Http\Controllers;

use App\Models\Wikipedia;

class WikiAPIController extends Controller
{
    /**
     * Redis cache key
     * @var string CACHE_KEY
     */
    const CACHE_KEY = 'wikipedia';

    /**
     * Redis expiry time, in seconds
     * @var int TIME_TO_EXPIRE
     */
    const TIME_TO_EXPIRE = 172800; // 2 days

    /**
     * List of countries to fetch
     *
     * @var array COUNTRIES
     */
    const COUNTRIES = [
        'United_Kingdom',
        'Netherlands',
        'Germany',
        'France',
        'Spain',
        'Italy',
        'Greece'
    ];

    /**
     * Wikipedia API URL
     * 
     * @var string URL
     */
    const URL = 'https://en.wikipedia.org/w/api.php';

    /**
     * Class constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns list of country articles' first paragraphs
     *
     * @return array
     */
    private function getCountryArticles(): array
    {
        $countryList = [];

        foreach (self::COUNTRIES as $country) {
            try {
                $url = self::URL . '?action=query&prop=extracts&format=json&titles=' . $country . '&exintro=1&explaintext=1';
                $countryList[] = $this->fetchData($url);
            } catch (\Exception $ex) {
                $countryList[] = ['error' => $ex->getMessge()];
            }
        }

        return $countryList;
    }

    /**
     * Parses articles' paragraphs
     *
     * @return array
     */
    private function getArticleInformation(): array
    {
        $articles = $this->getCountryArticles();
        $informationToReturn = [];

        foreach ($articles as $article) {
            $article = json_decode($article);
            $informationToReturn[] = $article->query->pages;
        }

        return $informationToReturn;
    }

    /**
     * Returns JSON-encoded results
     *
     * @return string
     */
    public function get(): string
    {
        if (!$this->client->exists(self::CACHE_KEY)) {
            $articles = $this->getArticleInformation();
            $informationToReturn = [];

            foreach ($articles as $article) {
                foreach ($article as $info) {
                    $article = new Wikipedia($info->extract, $info->title);
                    $informationToReturn[] = $article->get();
                }
            }

            // Set Redis cache
            $this->client->set(self::CACHE_KEY, json_encode($informationToReturn));
            $this->client->expire(self::CACHE_KEY, self::TIME_TO_EXPIRE);
        }

        return $this->client->get(self::CACHE_KEY);
    }
}
