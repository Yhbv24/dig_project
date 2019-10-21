<?php

namespace App\Http\Controllers;

use App\Http\Libs\Request;
class YouTubeAPIController extends Controller
{
    const URL = 'https://www.googleapis.com/youtube/v3/videos';
    
    /**
     * Returns list of top videos for a given set of countries
     *
     * @param integer $maxResults
     * @return array
     */
    private function getTopVideos(int $maxResults): array
    {
        $topVideos = [];

        foreach (parent::REGIONS as $region) {
            $url = self::URL . '?part=snippet&regionCode=' . $region . '&chart=mostpopular&maxResults='
            . $maxResults . '&key=' . YOUTUBE_KEY;
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
        $returnedVideos = [];
        $videos = json_encode($this->getTopVideos(1));
        $videos = json_decode($videos, true);

        for ($i = 0; $i < count($videos); $i++) {
            $videoInfo = json_decode($videos[$i], true)['items'][0];
            $returnedVideos[] = [
                'language' => self::REGIONS[$i],
                'description' => $videoInfo['snippet']['description'],
                'thumbnails' => [
                    $videoInfo['snippet']['thumbnails']['default'],
                    $videoInfo['snippet']['thumbnails']['high']
                ]
            ];
        }

        return $returnedVideos;
    }
}
