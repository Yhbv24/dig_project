<?php

namespace App\Models;

class Results
{
    /**
     * Result set count
     *
     * @var int
     */
    private $count = 0;

    /**
     * Result data
     *
     * @var array
     */
    private $data = [];

    /**
     * Sets the result set count
     *
     * @param integer $count
     * @return void
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * Sets the data
     *
     * @param integer $id
     * @param object $youTubeResults
     * @param object $wikiResults
     * @return void
     */
    public function setData(int $id, object $youTubeResults, object $wikiResults): void
    {
        $this->data[] = array_merge(
            ['id' => $id + 1],
            (array) $youTubeResults,
            (array) $wikiResults
        );
    }

    /**
     * Updates the entire data array
     *
     * @param array $data
     * @return void
     */
    public function updateData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Returns the data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns an array representation of this object
     *
     * @return array
     */
    public function get(): array
    {
        return [
            'result_count' => $this->count,
            'data' => $this->data
        ];
    }
}
