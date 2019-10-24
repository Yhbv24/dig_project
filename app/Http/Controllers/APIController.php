<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Results;

class APIController extends Controller
{   
    /**
     * Wikipedia API Controller
     *
     * @var WikiAPIController
     */
    private $wikiController;

    /**
     * YouTube API Controller
     *
     * @var YouTubeAPIController
     */
    private $youTubeController;

    /**
     * Class constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->wikiController = new WikiAPIController();
        $this->youTubeController = new YouTubeAPIController();
    }

    /**
     * Filters the request based on limit/offset
     *
     * @param Results $results
     * @param Request $request
     * @return array
     */
    private function filter(Results $results, Request $request): array
    {
        $data = $results->getData();

        if ($request->has('offset')) {
            $offset = $request->get('offset');

            $data = array_slice($data, $offset);
        }

        if ($request->has('limit')) {
            $limit = $request->get('limit');

            $data = array_slice($data, 0, $limit);
        }

        $results->updateData($data);
        $results->setCount(count($data));

        return $results->get();
    }

    /**
     * Returns the combined YouTube/Wikipedia information
     * as JSON
     *
     * @return string
     */
    public function get(Request $request): string
    {
        // Combine results of both Wikipedia and YouTube
        $youTubeResults = json_decode($this->youTubeController->get());
        $wikiResults = json_decode($this->wikiController->get());
        $results = new Results();

        // Build new array
        for ($i = 0; $i < count($youTubeResults); $i++) {
            $youtube = $youTubeResults[$i];
            $wiki = $wikiResults[$i];
            
            if ($youtube->language === $wiki->wiki_language) {
                unset($wiki->wiki_language);
                $results->setData($i, $youtube, $wiki);
            }
        }

        // Filter based on limit/offset
        $results = $this->filter($results, $request);

        return json_encode($results);
    }
}
