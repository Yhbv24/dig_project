<?php

namespace App\Http\Controllers;

class YouTubeAPIController extends Controller
{
    /**
     * YouTube API base URL
     * 
     * @var string URL
     */
    const URL = 'https://www.googleapis.com/youtube/v3/videos';

    /**
     * Redis cache key
     * 
     * @var string CACHE_KEY
     */
    const CACHE_KEY = 'youtube';

    /**
     * Redis cache time limit
     * 
     * @var int TIME_TO_EXPIRE
     */
    const TIME_TO_EXPIRE = 7200; // 2 hours

    /**
     * List of the regions to check
     * 
     * @var array REGIONS
     */
    const REGIONS = [
        'us',
        'nl',
        'de',
        'fr',
        'es',
        'it',
        'gr'
    ];

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Returns list of top videos for a given set of countries
     *
     * @param integer $maxResults
     * @return array
     */
    private function getTopVideos(int $maxResults): array
    {
        $topVideos = [];

        foreach (self::REGIONS as $region) {
            $url = self::URL . '?part=snippet&regionCode=' . $region . '&chart=mostpopular&maxResults='
            . $maxResults . '&key=' . YOUTUBE_KEY;
            $topVideos[] = $this->fetchData($url);
        }

        return $topVideos;
    }

    /**
     * Gets the top video for each country and returns them
     *
     * @param array $videos
     * @return array
     */
    private function getTopVideoForEachCountry(array $videos): array
    {
        $returnedVideos = [];

        for ($i = 0; $i < count($videos); $i++) {
            $videoInfo = json_decode($videos[$i], true)['items'][0];
            $returnedVideos[] = [
                'language' => self::REGIONS[$i],
                'youtube_description' => $videoInfo['snippet']['description'],
                'thumbnails' => [
                    $videoInfo['snippet']['thumbnails']['default'],
                    $videoInfo['snippet']['thumbnails']['high']
                ]
            ];
        }

        return $returnedVideos;
    }

    /**
     * Returns list of the descriptions and thumbnails
     * of a given set of popular videos and caches them
     *
     * @return string
     */
    public function get(): string
    {
        if (!$this->client->exists(self::CACHE_KEY)) {
            $returnedVideos = [];
            $videos = json_encode($this->getTopVideos(1));
            $videos = json_decode($videos, true);
            $returnedVideos = $this->getTopVideoForEachCountry($videos);

            // Set Redis cache
            $this->client->set(self::CACHE_KEY, json_encode($returnedVideos));
            $this->client->expire(self::CACHE_KEY, self::TIME_TO_EXPIRE);
        }

        return $this->client->get(self::CACHE_KEY);
    }
}
