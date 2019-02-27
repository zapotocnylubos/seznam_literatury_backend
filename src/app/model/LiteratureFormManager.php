<?php

namespace App\Models;

use Nette;

class LiteratureFormManager
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getLiteratureForms()
    {
        return $this->database->table('literature_forms');
    }

    public function getLiteratureFormsValuePairs()
    {
        return $this->getLiteratureForms()->fetchPairs('id', 'name');
    }

    public function getLiteratureForm($id)
    {
        return $this->getLiteratureForms()->get($id);
    }

    public function createLiteratureForm($data)
    {
        $this->getLiteratureForms()->insert($data);
    }

    public function updateLiteratureForm($id, $data)
    {
        $this->getLiteratureForm($id)->update($data);
    }

    public function deleteLiteratureForm($id)
    {
        $this->getLiteratureForm($id)->delete();
    }

    public function getLiteratureSetsRequiredLiteratureForms()
    {
        return $this->database->table('literature_sets_required_literature_forms');
    }

    public function getLiteratureSetLiteratureFormsSettings($literatureSetId)
    {
        return $this->getLiteratureSetsRequiredLiteratureForms()
            ->where(['literature_sets_id' => $literatureSetId]);
    }

    public function updateLiteratureSetLiteratureFormsSetting($literatureSetId, $literatureFormId, $minCount)
    {
        $setting = $this->getLiteratureSetsRequiredLiteratureForms()->where([
            'literature_sets_id' => $literatureSetId,
            'literature_forms_id' => $literatureFormId
        ]);

        if ($setting->valid()) {
            $setting->update([
                'min_count' => $minCount
            ]);
        } else {
            $this->getLiteratureSetsRequiredLiteratureForms()->insert([
                'literature_sets_id' => $literatureSetId,
                'literature_forms_id' => $literatureFormId,
                'min_count' => $minCount
            ]);
        }
    }
}