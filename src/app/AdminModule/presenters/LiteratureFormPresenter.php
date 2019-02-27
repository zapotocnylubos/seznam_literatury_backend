<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\LiteratureFormFormFactory;
use App\Models\LiteratureFormManager;

final class LiteratureFormPresenter extends BasePresenter
{
    /** @var LiteratureFormFormFactory */
    private $literatureFormFactory;

    /**  @var LiteratureFormManager */
    private $literatureFormManager;

    public function __construct(LiteratureFormFormFactory $formFormFactory, LiteratureFormManager $literatureFormManager)
    {
        parent::__construct();
        $this->literatureFormFactory = $formFormFactory;
        $this->literatureFormManager = $literatureFormManager;
    }

    public function actionUpdate($id)
    {
        $literatureForm = $this->literatureFormManager->getLiteratureForm($id);
        $this['literatureFormUpdateForm']->setDefaults($literatureForm);
    }

    public function actionLiteratureSetSettings($literatureSetId) {
        $this['literatureSetSettingsForm']->setDefaults(['literature_sets_id' => $literatureSetId]);

        // load all settings and set default values of each dynamic form field
        foreach($this->literatureFormManager->getLiteratureSetLiteratureFormsSettings($literatureSetId) as $literatureFormsSetting) {
            $this['literatureSetSettingsForm']->setDefaults([
                $literatureFormsSetting->literature_forms_id => $literatureFormsSetting->min_count
            ]);
        }
    }

    public function renderList()
    {
        $this->template->literatureForms = $this->literatureFormManager->getLiteratureForms();
    }

    public function handleDelete($id)
    {
        $this->literatureFormManager->deleteLiteratureForm($id);
        $this->flashMessage('Literární forma byla smazána.');
        $this->redirect('this');
    }

    public function createComponentLiteratureFormCreateForm()
    {
        return $this->literatureFormFactory->create(function () {
            $this->flashMessage('Literární forma byla vytvořena.');
            $this->redirect('LiteratureForm:list');
        });
    }

    public function createComponentLiteratureFormUpdateForm()
    {
        return $this->literatureFormFactory->update(function () {
            $this->flashMessage('Literární forma byla upravena.');
            $this->redirect('LiteratureForm:list');
        });
    }

    public function createComponentLiteratureSetSettingsForm()
    {
        return $this->literatureFormFactory->literatureSetSettings(function ($literature_set_id) {
            $this->flashMessage('Nastavení bylo uloženo.');
            $this->redirect('LiteratureSet:detail', $literature_set_id);
        });
    }
}
