<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\LiteratureGroupFormFactory;
use App\Models\LiteratureGroupManager;
use App\Models\LiteratureSetManager;

final class LiteratureGroupPresenter extends BasePresenter
{
    /** @var LiteratureGroupFormFactory */
    private $literatureGroupFactory;

    /**  @var LiteratureGroupManager */
    private $literatureGroupManager;

    /**  @var LiteratureSetManager */
    private $literatureSetManager;

    public function __construct(LiteratureGroupFormFactory $literatureGroupFactory, LiteratureGroupManager $literatureGroupManager, LiteratureSetManager $literatureSetManager)
    {
        parent::__construct();
        $this->literatureGroupFactory = $literatureGroupFactory;
        $this->literatureGroupManager = $literatureGroupManager;
        $this->literatureSetManager = $literatureSetManager;
    }

    public function actionUpdate($id)
    {
        $literatureSet = $this->literatureGroupManager->getLiteratureGroup($id);
        $this['literatureGroupUpdateForm']->setDefaults($literatureSet);
    }

    public function renderList($literatureSetId)
    {
        $this->template->literatureGroups = $this->literatureGroupManager
            ->getLiteratureGroups()
            ->where('literature_set_id', $literatureSetId);

        $this->template->literatureSet = $this->literatureSetManager->getLiteratureSet($literatureSetId);
    }

    public function handleDelete($id)
    {
        $this->literatureGroupManager->deleteLiteratureSet($id);
        $this->flashMessage('Literární skupina byla smazána.');
        $this->redirect('this');
    }

    public function createComponentLiteratureGroupCreateForm()
    {
        return $this->literatureGroupFactory->create(function () {
            $this->flashMessage('Literární skupina byla vytvořena.');
            $this->redirect('LiteratureGroup:list');
        });
    }

    public function createComponentLiteratureGroupUpdateForm()
    {
        return $this->literatureGroupFactory->update(function () {
            $this->flashMessage('Literární skupina byla upravena.');
            $this->redirect('LiteratureGroup:list');
        });
    }
}
