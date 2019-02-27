<?php

namespace App\Models;

use Nette;

class LiteratureSetManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getLiteratureSets()
    {
        return $this->database->table('literature_sets');
    }

    public function getLiteratureSetValuePairs()
    {
        return $this->getLiteratureSets()->fetchPairs('id', 'period');
    }

    public function getLiteratureSet($id)
    {
        return $this->getLiteratureSets()->get($id);
    }

    public function createLiteratureSet($data)
    {
        return $this->getLiteratureSets()->insert($data);
    }

    public function updateLiteratureSet($id, $data)
    {
        $this->getLiteratureSet($id)->update($data);
    }

    public function deleteLiteratureSet($id)
    {
        $this->getLiteratureSet($id)->delete();
    }

    public function getActiveLiteratureSet()
    {
        return $this->getLiteratureSets()->where('is_active', 1)->fetch();
    }

    public function setActiveLiteratureSet($id)
    {
        if ($currentlyActive = $this->getActiveLiteratureSet()) {
            $currentlyActive->update(['is_active' => 0]);
        }

        if ($futureActive = $this->getLiteratureSet($id)) {
            $futureActive->update(['is_active' => 1]);
        }
    }
}