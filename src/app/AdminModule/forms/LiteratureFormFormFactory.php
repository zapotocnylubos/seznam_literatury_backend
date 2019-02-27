<?php

namespace App\AdminModule\Forms;

use App\Models\LiteratureFormManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;


final class LiteratureFormFormFactory
{
    use Nette\SmartObject;

    /** @var FormFactory */
    private $factory;

    /** @var LiteratureFormManager */
    private $literatureFormManager;


    public function __construct(FormFactory $factory, LiteratureFormManager $literatureFormManager)
    {
        $this->factory = $factory;
        $this->literatureFormManager = $literatureFormManager;
    }

    /**
     * @param callable $onSuccess
     * @return \Czubehead\BootstrapForms\BootstrapForm
     */
    public function create(callable $onSuccess)
    {
        $form = $this->factory->create();
        $form->addText('name', 'Název literární formy:')
            ->setRequired('Zadejte prosím název literární formy.');

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->literatureFormManager->createLiteratureForm($values);
            } catch (UniqueConstraintViolationException $e) {
                $form['name']->addError('Literární forma s tímto jménem již existuje.');
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

        $form->addText('name', 'Název literární formy:')
            ->setRequired('Zadejte prosím název literární formy.');

        $form->addSubmit('update', 'Upravit');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                $this->literatureFormManager->updateLiteratureForm($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form['name']->addError('Literární forma s tímto jménem již existuje.');
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
    public function literatureSetSettings(callable $onSuccess)
    {
        $form = $this->factory->create();

        $form->addHidden('literature_sets_id');

        $form->addGroup('Literární formy'); // Following elements will be in this group

        foreach ($this->literatureFormManager->getLiteratureFormsValuePairs() as $id => $name) {
            $form->addInteger($id, $name);
        }

        $form->addGroup(null); // FIX - to put button underneath fieldset

        $form->addSubmit('save', 'Uložit nastavení');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                foreach ($form->getGroups()['Literární formy']->getControls() as $control) {
                    $this->literatureFormManager->updateLiteratureSetLiteratureFormsSetting($values->literature_sets_id, $control->name, $control->value);
                }
            } catch (\Exception $e) {
                $form->addError($e->getMessage());
                return;
            }
            $onSuccess($values->literature_sets_id);
        };

        return $form;
    }
}
