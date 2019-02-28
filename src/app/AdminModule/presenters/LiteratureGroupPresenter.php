<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\LiteratureGroupFormFactory;
use App\Helpers\OrderHelper;
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

    public function actionCreate($literatureSetId)
    {
        $this['literatureGroupCreateForm']->setDefaults(['literature_set_id' => $literatureSetId]);
    }

    public function actionUpdate($id)
    {
        $literatureSet = $this->literatureGroupManager->getLiteratureGroup($id);
        $this['literatureGroupUpdateForm']->setDefaults($literatureSet);
    }

    public function actionReorderDown($id)
    {
        $currentLiteratureGroup = $this->literatureGroupManager->getLiteratureGroup($id);

        $literatureGroups = $this->literatureGroupManager->getLiteratureGroups()
            ->where('literature_set_id', $currentLiteratureGroup->literature_set_id)
            ->order('sort_order', 'DESC');


        $ids = [];
        foreach ($literatureGroups as $id => $group) {
            $ids[] = $id;
        }

        $ids = OrderHelper::ChangeOrderDown($ids, $currentLiteratureGroup->id);

        $this->literatureGroupManager->reindexGroupsOrder($ids);

        $this->redirect('LiteratureSet:detail', $currentLiteratureGroup->literature_set_id);
    }

    public function actionReorderUp($id)
    {
        $currentLiteratureGroup = $this->literatureGroupManager->getLiteratureGroup($id);

        $literatureGroups = $this->literatureGroupManager->getLiteratureGroups()
            ->where('literature_set_id', $currentLiteratureGroup->literature_set_id)
            ->order('sort_order', 'DESC');

        $ids = [];
        foreach ($literatureGroups as $id => $group) {
            $ids[] = $id;
        }

        $ids = OrderHelper::ChangeOrderUp($ids, $currentLiteratureGroup->id);

        $this->literatureGroupManager->reindexGroupsOrder($ids);

        $this->redirect('LiteratureSet:detail', $currentLiteratureGroup->literature_set_id);
    }

    public function actionDelete($id)
    {
        $literatureSetId = $this->literatureGroupManager->getLiteratureGroup($id)->literature_set_id;
        $this->literatureGroupManager->deleteLiteratureGroup($id);
        $this->flashMessage('Literární skupina byla smazána.');
        $this->redirect('LiteratureSet:detail', $literatureSetId);
    }

    public function createComponentLiteratureGroupCreateForm()
    {
        return $this->literatureGroupFactory->create(function ($literature_set_id) {
            $this->flashMessage('Literární skupina byla vytvořena.');
            $this->redirect('LiteratureSet:detail', $literature_set_id);
        });
    }

    public function createComponentLiteratureGroupUpdateForm()
    {
        return $this->literatureGroupFactory->update(function ($literature_set_id) {
            $this->flashMessage('Literární skupina byla upravena.');
            $this->redirect('LiteratureSet:detail', $literature_set_id);
        });
    }
}
