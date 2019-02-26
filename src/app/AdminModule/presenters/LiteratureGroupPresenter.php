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

    public function actionUpdate($id)
    {
        $literatureSet = $this->literatureGroupManager->getLiteratureGroup($id);
        $this['literatureGroupUpdateForm']->setDefaults($literatureSet);
    }

    public function renderList($literatureSetId)
    {
        $this->template->literatureSet = $literatureSet = $this->literatureSetManager->getLiteratureSet($literatureSetId);

        $this->template->literatureGroups = $literatureSet->related('literature_groups')
            ->order('sort_order', 'DESC');
    }

    public function handleReorderDown($id)
    {
        $currentLiteratureGroup = $this->literatureGroupManager->getLiteratureGroup($id);

        $literatureGroups = $this->literatureGroupManager->getLiteratureGroups()
            ->where('literature_set_id', $currentLiteratureGroup->literature_set_id)
            ->order('sort_order', 'DESC');


        $ids = [];
        foreach($literatureGroups as $id => $group) {
            $ids[] = $id;
        }

        $ids = OrderHelper::ChangeOrderDown($ids, $currentLiteratureGroup->id);

        $this->literatureGroupManager->reindexGroupsOrder($ids);

        $this->redirect('this');
    }

    public function handleReorderUp($id)
    {
        $currentLiteratureGroup = $this->literatureGroupManager->getLiteratureGroup($id);

        $literatureGroups = $this->literatureGroupManager->getLiteratureGroups()
            ->where('literature_set_id', $currentLiteratureGroup->literature_set_id)
            ->order('sort_order', 'DESC');

        $ids = [];
        foreach($literatureGroups as $id => $group) {
            $ids[] = $id;
        }

        $ids = OrderHelper::ChangeOrderUp($ids, $currentLiteratureGroup->id);

        $this->literatureGroupManager->reindexGroupsOrder($ids);

        $this->redirect('this');
    }

    public function handleDelete($id)
    {
        $this->literatureGroupManager->deleteLiteratureSet($id);
        $this->flashMessage('Literární skupina byla smazána.');
        $this->redirect('this');
    }

    public function createComponentLiteratureGroupCreateForm()
    {
        return $this->literatureGroupFactory->create(function ($literature_set_id) {
            $this->flashMessage('Literární skupina byla vytvořena.');
            $this->redirect('LiteratureGroup:list', ['literatureSetId' => $literature_set_id]);
        });
    }

    public function createComponentLiteratureGroupUpdateForm()
    {
        return $this->literatureGroupFactory->update(function ($literature_set_id) {
            $this->flashMessage('Literární skupina byla upravena.');
            $this->redirect('LiteratureGroup:list', ['literatureSetId' => $literature_set_id]);
        });
    }
}
