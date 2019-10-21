<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/**
 * Returns the JSON representation of the YouTube videos with
 * their associated Wikipedia descriptions
 * Rate limited to 20 requests per minute
 */
$router->group(['middleware' => 'throttle:20'], function () use ($router) {
    $router->get('/api/countries', [
        'uses' => 'YouTubeAPIController@getVideoInformation'
    ]);
});