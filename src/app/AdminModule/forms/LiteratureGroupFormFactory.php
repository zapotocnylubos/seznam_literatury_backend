<?php

namespace App\AdminModule\Forms;

use App\Models\LiteratureGroupManager;
use App\Models\LiteratureSetManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;


final class LiteratureGroupFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var LiteratureGroupManager */
    private $setManager;

    /** @var LiteratureGroupManager */
    private $groupManager;


    public function __construct(FormFactory $factory, LiteratureSetManager $setManager, LiteratureGroupManager $groupManager)
    {
        $this->factory = $factory;
        $this->setManager = $setManager;
        $this->groupManager = $groupManager;
    }

    /**
     * @param callable $onSuccess
     * @return \Czubehead\BootstrapForms\BootstrapForm
     */
    public function create(callable $onSuccess)
    {
        $form = $this->factory->create();

        $options = ['' => '------'];
        $options += $this->setManager->getLiteratureSetValuePairs();

        $form->addSelect('literature_set_id', 'Literární set:', $options);

        $form->addText('title', 'Název skupiny:')
            ->setRequired('Zadejte prosím název skupiny.');

        $form->addInteger('min_count', 'Min. knih z této skupiny:')
            ->setRequired('Zadejte prosím minimum knih z této skupiny.');

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->groupManager->createLiteratureGroup($values);
            } catch (UniqueConstraintViolationException $e) {
                $form['title']->addError('Skupina literatury s tímto názevm v tomto ročníku již existuje.');
                return;
            }
            $onSuccess($values->literature_set_id);
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

        $options = ['' => '------'];
        $options += $this->setManager->getLiteratureSetValuePairs();

        $form->addSelect('literature_set_id', 'Literární set:', $options);

        $form->addText('title', 'Název skupiny:')
            ->setRequired('Zadejte prosím název skupiny.');

        $form->addInteger('min_count', 'Min. knih z této skupiny:')
            ->setRequired('Zadejte prosím minimum knih z této skupiny.');

        $form->addSubmit('update', 'Upravit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->groupManager->updateLiteratureGroup($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form['title']->addError('Skupina literatury s tímto názevm v tomto ročníku již existuje.');
                return;
            }
            $onSuccess($values->literature_set_id);
        };

        return $form;
    }
}
