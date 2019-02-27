<?php

namespace App\AdminModule\Forms;

use App\Models\LiteratureSetManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;


final class LiteratureSetFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var LiteratureSetManager */
    private $setManager;


    public function __construct(FormFactory $factory, LiteratureSetManager $setManager)
    {
        $this->factory = $factory;
        $this->setManager = $setManager;
    }

    /**
     * @param callable $onSuccess
     * @return \Czubehead\BootstrapForms\BootstrapForm
     */
    public function create(callable $onSuccess)
    {
        $form = $this->factory->create();
        $form->addText('period', 'Ročník:')
            ->setRequired('Zadejte prosím ročník.');

        $form->addInteger('author_max_count', 'Max. knih od stejného autora:')
            ->setRequired('Zadejte prosím maximum knih od stejného autora.');

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->setManager->createLiteratureSet($values);
            } catch (UniqueConstraintViolationException $e) {
                $form['period']->addError('Set literatury s tímto ročníkem již existuje.');
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

        $form->addText('period', 'Ročník:')
            ->setRequired('Zadejte prosím ročník.');

        $form->addInteger('author_max_count', 'Max. knih od stejného autora:')
            ->setRequired('Zadejte prosím maximum knih od stejného autora.');

        $form->addSubmit('update', 'Upravit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->setManager->updateLiteratureSet($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form['period']->addError('Set literatury s tímto ročníkem již existuje.');
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
    public function active(callable $onSuccess)
    {
        $form = $this->factory->create();

        $options = [null => '------'];
        $options += $this->setManager->getLiteratureSetValuePairs();

        // not required because you can disable current literature set
        $form->addSelect('literature_set_id', 'Literární set:', $options);

        $form->addSubmit('update', 'Změnit aktivní literární set');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->setManager->setActiveLiteratureSet($values->literature_set_id);
            } catch (\Exception $e) {
                $form->addError($e->getMessage());
                return;
            }
            $onSuccess();
        };

        return $form;
    }
}
