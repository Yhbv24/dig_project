<?php

namespace App\Models;

class YouTube
{
    /**
     * Video language
     *
     * @var string
     */
    private $language;

    /**
     * Video description
     *
     * @var string
     */
    private $description;

    /**
     * Thumbnails array
     *
     * @var array
     */
    private $thumbnails = [];

    /**
     * Class constructor
     *
     * @param string $language
     * @param string $description
     * @param array $thumbnails
     */
    public function __construct(string $language, string $description, array $thumbnails)
    {
        $this->language = $language;
        $this->description = $description;
        $this->thumbnails = $thumbnails;
    }

    /**
     * Returns this object represented as array
     *
     * @return array
     */
    public function get(): array
    {
        return [
            'language' => $this->language,
            'youtube_description' => $this->description,
            'thumbnails' => $this->thumbnails
        ];
    }
}
