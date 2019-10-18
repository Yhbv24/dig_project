<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class WikiAPIController extends Controller
{
    /**
     * @var array Languages to check
     * Note: Some languages do not work
     */
    const LANGUAGES = [
        'en',
        'de',
        'fr'
    ];

    /**
     * Hits the Wikipedia API and returns a list of the feature articles
     *
     * @return string
     */
    private function getFeaturedArticles(): array
    {
        $featureArticles = [];

        foreach (self::LANGUAGES as $language) {
            $wikiFeed = implode(file('https://' . $language . '.wikipedia.org/w/api.php?action=featuredfeed&feed=featured&feedformat=atom'));
            $parsedXml = simplexml_load_string($wikiFeed);
            $parsedJson = json_encode($parsedXml);
            $featuredArticles[] = json_decode($parsedJson, true);
        }

        return $featuredArticles;
    }

    /**
     * Gets the current articles based on today's date
     *
     * @return void
     */
    private function getCurrentArticles(): array
    {
        $featuredArticles = [];
        $articles = $this->getFeaturedArticles();

        foreach ($articles as $article) {
            foreach ($article['entry'] as $todayArticle) {
                $articleDate = explode('T', $todayArticle['updated'])[0];

                if ($articleDate === date('Y-m-d')) {
                    $featuredArticles[] = $todayArticle;
                }
            }
        }

        return $featuredArticles;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getArticleSummaries(): string
    {
        $articles = $this->getCurrentArticles();
        $summaries = [];

        foreach ($articles as $article) {
            $summaries[] = ['summary' => strip_tags($article['summary'])];
        }

        return json_encode($summaries);
    }
}
