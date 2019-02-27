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

    public function getLiteratureSetsRequiredGenres()
    {
        return $this->database->table('literature_sets_required_genres');
    }

    public function getGenreValuePairs()
    {
        return $this->getGenres()->fetchPairs('id', 'name');
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

    public function getLiteratureSetGenresSettings($literatureSetId) {
        return $this->getLiteratureSetsRequiredGenres()->where(['literature_sets_id' => $literatureSetId]);
    }

    public function updateLiteratureSetGenreSetting($literatureSetId, $genreId, $minCount) {
        $setting = $this->getLiteratureSetsRequiredGenres()->where([
            'literature_sets_id' => $literatureSetId,
            'genres_id' => $genreId
        ]);

        if($setting->valid()) {
            $setting->update([
                'min_count' => $minCount
            ]);
        } else {
            $this->getLiteratureSetsRequiredGenres()->insert([
                'literature_sets_id' => $literatureSetId,
                'genres_id' => $genreId,
                'min_count' => $minCount
            ]);
        }
    }

    public function deleteGenre($id)
    {
        $this->getGenre($id)->delete();
    }
}