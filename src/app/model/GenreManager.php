<?php

namespace App\Models;

use Nette;

class GenreManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getGenres()
    {
        return $this->database->table('genres');
    }

    public function getGenreValuePairs()
    {
        $valuePairs = [];
        foreach ($this->getGenres() as $genre) {
            $valuePairs[$genre->id] = $genre->name;
        }
        return $valuePairs;
    }

    public function getGenre($id)
    {
        return $this->getGenres()->get($id);
    }

    public function createGenre($data)
    {
        $this->getGenres()->insert($data);
    }

    public function updateGenre($id, $data)
    {
        $this->getGenre($id)->update($data);
    }

    public function deleteGenre($id)
    {
        $this->getGenre($id)->delete();
    }
}