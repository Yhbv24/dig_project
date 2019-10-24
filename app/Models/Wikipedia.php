<?php

namespace App\Models;

class Wikipedia
{
    /**
     * Article description
     *
     * @var string
     */
    private $description;

    /**
     * Article title
     *
     * @var string
     */
    private $title;

    /**
     * Class constructor
     *
     * @param string $language
     * @param string $description
     * @param string $title
     */
    public function __construct(string $description, string $title)
    {
        $this->description = $description;
        $this->title = $title;
    }

    /**
     * Returns this object represented as an array
     *
     * @return array
     */
    public function get(): array
    {
        return [
            'wiki_description' => $this->description,
            'country_title' => $this->title
        ];
    }
}
