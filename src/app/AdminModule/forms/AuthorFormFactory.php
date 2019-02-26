<?php

namespace App\AdminModule\Forms;

use App\Models\AuthorManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;


final class AuthorFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var AuthorManager */
    private $manager;


    public function __construct(FormFactory $factory, AuthorManager $manager)
    {
        $this->factory = $factory;
        $this->manager = $manager;
    }

    /**
     * @param callable $onSuccess
     * @return \Czubehead\BootstrapForms\BootstrapForm
     */
    public function create(callable $onSuccess)
    {
        $form = $this->factory->create();
        $form->addText('full_name', 'Jméno autora:')
            ->setRequired('Zadejte prosím jméno autora.');

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->manager->createAuthor($values);
            } catch (UniqueConstraintViolationException $e) {
                $form['full_name']->addError('Autor s tímto jménem již existuje.');
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

        $form->addText('full_name', 'Jméno autora:')
            ->setRequired('Zadejte prosím jméno autora.');

        $form->addSubmit('update', 'Upravit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->manager->updateAuthor($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form['full_name']->addError('Autor s tímto jménem již existuje.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
