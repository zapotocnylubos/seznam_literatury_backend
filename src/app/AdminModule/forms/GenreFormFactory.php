<?php

namespace App\AdminModule\Forms;

use App\Models\GenreManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;


final class GenreFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var GenreManager */
    private $manager;


    public function __construct(FormFactory $factory, GenreManager $manager)
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
        $form->addText('name', 'Název žánru:')
            ->setRequired('Zadejte prosím název žánru.');

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->manager->createGenre($values);
            } catch (UniqueConstraintViolationException $e) {
                $form->controls['name']->addError('Žánr s tímto jménem již existuje.');
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

        $form->addText('name', 'Název žánru:')
            ->setRequired('Zadejte prosím název žánru.');

        $form->addSubmit('update', 'Upravit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->manager->updateGenre($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form->controls['name']->addError('Žánr s tímto jménem již existuje.');
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
