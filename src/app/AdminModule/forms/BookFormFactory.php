<?php

namespace App\AdminModule\Forms;

use App\Models\AuthorManager;
use App\Models\BookManager;
use App\Models\GenreManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;


final class BookFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var BookManager */
    private $bookManager;

    /** @var AuthorManager */
    private $authorManager;

    /** @var GenreManager */
    private $genreManager;


    public function __construct(
        FormFactory $factory,
        BookManager $bookManager,
        AuthorManager $authorManager,
        GenreManager $genreManager
    )
    {
        $this->factory = $factory;
        $this->bookManager = $bookManager;
        $this->authorManager = $authorManager;
        $this->genreManager = $genreManager;
    }

    /**
     * @param callable $onSuccess
     * @return \Czubehead\BootstrapForms\BootstrapForm
     */
    public function create(callable $onSuccess)
    {
        $form = $this->factory->create();
        $form->addText('title', 'Název knihy:')
            ->setRequired('Zadejte prosím název knihy.');

        $options = [null => '------'];
        $options += $this->authorManager->getAuthorValuePairs();
        $form->addSelect('author_id', 'Autor:', $options);

        $options = [null => '------'];
        $options += $this->genreManager->getGenreValuePairs();
        $form->addSelect('genre_id', 'Žánr:', $options);

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->bookManager->createBook($values);
            } catch (UniqueConstraintViolationException $e) {
                $form['name']->addError('Kniha s tímto názvem již existuje.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }

    /**
     * @param callable $onSuccess
     * @return \Czubehead\BootstrapForms\BootstrapForm
     */
    public function update(callable $onSuccess)
    {
        $form = $this->factory->create();

        $form->addHidden('id');

        $form->addText('title', 'Název knihy:')
            ->setRequired('Zadejte prosím název knihy.');

        $options = [null => '------'];
        $options += $this->authorManager->getAuthorValuePairs();
        $form->addSelect('author_id', 'Autor:', $options);

        $options = [null => '------'];
        $options += $this->genreManager->getGenreValuePairs();
        $form->addSelect('genre_id', 'Žánr:', $options);

        $form->addSubmit('update', 'Upravit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->bookManager->updateBook($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form['name']->addError('Kniha s tímto názvem již existuje.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }

    public function assign(callable $onSuccess)
    {
        $form = $this->factory->create();

        $form->addHidden('literature_groups_id');

        $options = [null => '------'];
        $options += $this->bookManager->getBookValuePairs();
        $form->addSelect('books_id', 'Kniha:', $options);

        $form->addSubmit('assign', 'Přiřadit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->bookManager->assignToGroup($values);
            } catch (\Exception $e) {
                $form->addError($e->getMessage());
                return;
            }
            $onSuccess($values->literature_groups_id);
        };

        return $form;
    }
}
