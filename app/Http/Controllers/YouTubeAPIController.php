<?php

namespace App\Http\Controllers;

class YouTubeAPIController extends Controller
{
    const REGIONS = [
        'us',
        'nl',
        'de',
        'fr',
        'es',
        'it',
        'gr'
    ];

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
            $url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&regionCode=' . $region . '&chart=mostpopular&maxResults=' . $maxResults. '&key=' . YOUTUBE_KEY;
            $topVideos[] = parent::fetchData($url);
        }

        return $topVideos;
    }

    /**
     * Returns list of the descriptions and thumbnails of a given set of popularvideos
     *
     * @return array
     */
    public function getVideoInformation(): array
    {
        $counter = 0;
        $returnedVideos = [];
        $videos = json_encode($this->getTopVideos(1));
        $videos = json_decode($videos, true);

        foreach ($videos as $video) {
            $videoInfo = json_decode($video, true)['items'][0];
            $returnedVideos[] = [
                'language' => self::REGIONS[$counter],
                'description' => $videoInfo['snippet']['description'],
                'thumbnails' => [
                    $videoInfo['snippet']['thumbnails']['default'],
                    $videoInfo['snippet']['thumbnails']['high']
                ]
            ];
            $counter++;
        }

        return $returnedVideos;
    }
}
