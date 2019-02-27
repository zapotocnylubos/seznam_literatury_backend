<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\BookFormFactory;
use App\Helpers\OrderHelper;
use App\Models\BookManager;
use App\Models\LiteratureGroupManager;

final class BookPresenter extends BasePresenter
{
    /** @var BookFormFactory */
    private $bookFactory;

    /**  @var BookManager */
    private $bookManager;

    /**  @var LiteratureGroupManager */
    private $groupManager;

    public function __construct(BookFormFactory $bookFactory, BookManager $bookManager, LiteratureGroupManager $groupManager)
    {
        parent::__construct();
        $this->bookFactory = $bookFactory;
        $this->bookManager = $bookManager;
        $this->groupManager = $groupManager;
    }

    public function actionUpdate($id)
    {
        $book = $this->bookManager->getBook($id);
        $this['bookUpdateForm']->setDefaults($book);
    }

    public function actionAssign($literatureGroupId)
    {
        $this['assignBookForm']->setDefaults(['literature_groups_id' => $literatureGroupId]);
    }

    public function actionUnassign($literatureGroupsHasBooksId)
    {
        $groupBook = $this->bookManager->getLiteratureGroupBook($literatureGroupsHasBooksId);
        $literatureSetId = $groupBook->literature_group->literature_set_id;

        $this->bookManager->unassignFromGroup($literatureGroupsHasBooksId);

        $this->flashMessage('Kniha byla odebrána.');
        $this->redirect('LiteratureSet:detail', $literatureSetId);
    }

    public function actionReorderDown($literatureGroupsHasBooksId)
    {
        $currentGroupBook = $this->bookManager->getLiteratureGroupBook($literatureGroupsHasBooksId);

        $groupBooks = $this->bookManager->getLiteratureGroupsHasBooks()
            ->where('literature_groups_id', $currentGroupBook->literature_groups_id)
            ->order('sort_order', 'DESC');


        $ids = [];
        foreach ($groupBooks as $groupBook) {
            $ids[] = $groupBook->id;
        }

        $ids = OrderHelper::ChangeOrderDown($ids, $currentGroupBook->id);

        $this->bookManager->reindexLiteratureGroupBooksOrder($ids);

        $this->redirect('LiteratureSet:detail', $currentGroupBook->literature_group->literature_set_id);
    }

    public function actionReorderUp($literatureGroupsHasBooksId)
    {
        $currentGroupBook = $this->bookManager->getLiteratureGroupBook($literatureGroupsHasBooksId);

        $groupBooks = $this->bookManager->getLiteratureGroupsHasBooks()
            ->where('literature_groups_id', $currentGroupBook->literature_groups_id)
            ->order('sort_order', 'DESC');

        $ids = [];
        foreach ($groupBooks as  $groupBook) {
            $ids[] = $groupBook->id;
        }

        $ids = OrderHelper::ChangeOrderUp($ids, $currentGroupBook->id);

        $this->bookManager->reindexLiteratureGroupBooksOrder($ids);

        $this->redirect('LiteratureSet:detail', $currentGroupBook->literature_group->literature_set_id);
    }

    public function renderList()
    {
        $this->template->books = $this->bookManager->getBooks();
    }

    public function handleDelete($id)
    {
        $this->bookManager->deleteBook($id);
        $this->flashMessage('Kniha byla smazána.');
        $this->redirect('this');
    }

    public function createComponentBookCreateForm()
    {
        return $this->bookFactory->create(function () {
            $this->flashMessage('Kniha byla vytvořena.');
            $this->redirect('Book:list');
        });
    }

    public function createComponentBookUpdateForm()
    {
        return $this->bookFactory->update(function () {
            $this->flashMessage('Kniha byla upravena.');
            $this->redirect('Book:list');
        });
    }

    public function createComponentAssignBookForm()
    {
        return $this->bookFactory->assign(function ($literature_group_id) {
            $literatureGroup = $this->groupManager->getLiteratureGroup($literature_group_id);

            $this->flashMessage('Kniha byla přiřazena.');
            $this->redirect('LiteratureSet:detail', $literatureGroup->literature_set_id);
        });
    }
}
