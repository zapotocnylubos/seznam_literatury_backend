<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\AuthorFormFactory;
use App\Models\AuthorManager;

final class AuthorPresenter extends BasePresenter
{
    /** @var AuthorFormFactory */
    private $authorFactory;

    /**  @var AuthorManager */
    private $authorManager;

    public function __construct(AuthorFormFactory $authorFactory, AuthorManager $authorManager)
    {
        parent::__construct();
        $this->authorFactory = $authorFactory;
        $this->authorManager = $authorManager;
    }

    public function actionUpdate($id)
    {
        $author = $this->authorManager->getAuthor($id);
        $this['authorUpdateForm']->setDefaults($author);
    }

    public function renderList()
    {
        $this->template->authors = $this->authorManager->getAuthors();
    }

    public function handleDelete($id)
    {
        $this->authorManager->deleteAuthor($id);
        $this->flashMessage('Autor byl smazán.');
        $this->redirect('this');
    }

    public function createComponentAuthorCreateForm()
    {
        return $this->authorFactory->create(function () {
            $this->flashMessage('Autor byl vytvořen.');
            $this->redirect('Author:list');
        });
    }

    public function createComponentAuthorUpdateForm()
    {
        return $this->authorFactory->update(function () {
            $this->flashMessage('Autor byl upraven.');
            $this->redirect('Author:list');
        });
    }
}
