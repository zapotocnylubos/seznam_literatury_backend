<?php

namespace App\Models;

use Nette;

class LiteratureGroupManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getLiteratureGroups()
    {
        return $this->database->table('literature_groups');
    }

    public function getLiteratureGroupValuePairs()
    {
        return $this->getLiteratureGroups()->fetchPairs('id', 'title');
    }

    public function reindexGroupsOrder($ids) {
        for ($i = 0; $i < count($ids) ; $i++) {
            $this->getLiteratureGroup($ids[$i])
                ->update(['sort_order' => (count($ids) - 1) - $i]);
        }
    }

    public function getLiteratureGroup($id)
    {
        return $this->getLiteratureGroups()->get($id);
    }

    public function createLiteratureGroup($data)
    {
        $this->getLiteratureGroups()->insert($data);
    }

    public function updateLiteratureGroup($id, $data)
    {
        $this->getLiteratureGroup($id)->update($data);
    }

    public function deleteLiteratureSet($id)
    {
        $this->getLiteratureGroup($id)->delete();
    }
}