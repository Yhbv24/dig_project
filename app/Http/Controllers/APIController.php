<?php

namespace App\Http\Controllers;

use App\Http\Libs\Request;

class APIController extends Controller
{   
    private $wikiController;
    private $youTubeController;

    public function __construct()
    {
        parent::__construct();
        $this->wikiController = new WikiAPIController();
        $this->youTubeController = new YouTubeAPIController();
    }

    /**
     * Returns the combined YouTube/Wikipedia information
     * as JSON
     *
     * @return string
     */
    public function get(Request $request): string
    {
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $offset = $request->has('offset') ? $request->get('offset') : 10;

        
        $youTubeResults = json_decode($this->youTubeController->get());
        $wikiResults = json_decode($this->wikiController->get());
        $returned = [];

        for ($i = 0; $i < count($youTubeResults); $i++) {
            $returned[] = array_merge((array) $youTubeResults[$i], (array) $wikiResults[$i]);
        }

        return json_encode($returned);
    }
}
