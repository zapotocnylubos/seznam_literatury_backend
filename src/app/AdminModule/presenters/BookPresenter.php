<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\BookFormFactory;
use App\Models\BookManager;

final class BookPresenter extends BasePresenter
{
    /** @var BookFormFactory */
    private $bookFactory;

    /**  @var BookManager */
    private $bookManager;

    public function __construct(BookFormFactory $bookFactory, BookManager $bookManager)
    {
        parent::__construct();
        $this->bookFactory = $bookFactory;
        $this->bookManager = $bookManager;
    }

    public function actionUpdate($id)
    {
        $book = $this->bookManager->getBook($id);
        $this['bookUpdateForm']->setDefaults($book);
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
}
