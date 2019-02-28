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

    public function reindexGroupsOrder($ids)
    {
        for ($i = 0; $i < count($ids); $i++) {
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
        $data['sort_order'] = count($this->getLiteratureGroups()->where(['literature_set_id' => $data['literature_set_id']]));

        $this->getLiteratureGroups()->insert($data);
    }

    public function updateLiteratureGroup($id, $data)
    {
        $this->getLiteratureGroup($id)->update($data);
    }

    public function deleteLiteratureGroup($id)
    {
        $literatureGroup = $this->getLiteratureGroup($id);
        $literature_set_id = $literatureGroup->literature_set_id;
        $literatureGroup->delete();

        $ids = [];
        foreach ($this->getLiteratureGroups()->where('literature_set_id', $literature_set_id) as $literatureGroupRow) {
            $ids[] = $literatureGroupRow->id;
        }

        $this->reindexGroupsOrder($ids);
    }
}