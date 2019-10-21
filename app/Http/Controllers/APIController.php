<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @param array $results
     * @param Request $request
     * @return array
     */
    private function filter(array &$results, Request $request): array
    {
        $filteredResults = $results;
        $data = $results['data'];

        if ($request->has('offset')) {
            $offset = $request->get('offset');

            $data = array_slice($data, $offset);
        }

        if ($request->has('limit')) {
            $limit = $request->get('limit');

            $data = array_slice($data, 0, $limit);
        }

        $filteredResults['data'] = $data;
        $filteredResults['result_count'] = count($data);

        return $filteredResults;
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
        $results = [
            'result_count' => count($youTubeResults),
            'data' => []
        ];

        // Build new array
        for ($i = 0; $i < count($youTubeResults); $i++) {
            $results['data'][] = array_merge(['id' => $i + 1], (array) $youTubeResults[$i], (array) $wikiResults[$i]);
        }

        // Filter based on limit/offset
        $results = $this->filter($results, $request);

        return json_encode($results);
    }
}
