### Wikipedia/YouTube Combiner

Created by Ash Laidlaw

### Tech Used

This project makes use of:
* PHP 7.3.10
* Redis 5.0.6
* Lumen 6.0.0
* Composer

### Startup Instructions

In order to get started, you must have PHP >7.3 installed, along with Redis >5 and Lumen >6.

1. Run `git clone https://github.com/Yhbv24/dig_project` to clone the project.
2. Run `composer install` to make sure the dependencies are installed.
3. In the root directory, add  a file called `youtube_api_key.php`, and in that file, add `define('YOUTUBE_KEY', <your api key>);` . 
4. Navigate to the root directory, and run `redis-server` to start the Redis server.
5. In another Terminal tab, run `php
php -S localhost:8000 -t public` to start the server.
6. To hit the endpoint, go to localhost:800/api/results. You can also add an offset and/or limit by adding ?offet= or ?limit=.

### JSON Format

The JSON format will be:
```
{
	"result_count": 7,
	"data": [
		{
			"id": 2,
			"language": "nl",
			"youtube_description": "XYZ",
			"thumbnails": [
				{
					"url": "http://www.xyz.com...",
					"width": 120,
					"height: 90
				}
			],
			"wiki_description": "The Netherlands...",
			"country_title": "Netherlands"
		}
	]
}
```

### Things to Note

There is a rate limit set to 20 hits every 10 minutes. This can be changed by going to `routes/web.php` and changing line 23 from `'throttle:20,10'` whatever you want.