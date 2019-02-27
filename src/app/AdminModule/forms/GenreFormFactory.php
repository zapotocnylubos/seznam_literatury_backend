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
    private $genreManager;


    public function __construct(FormFactory $factory, GenreManager $manager)
    {
        $this->factory = $factory;
        $this->genreManager = $manager;
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
                $this->genreManager->createGenre($values);
            } catch (UniqueConstraintViolationException $e) {
                $form['name']->addError('Žánr s tímto jménem již existuje.');
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
                $this->genreManager->updateGenre($values->id, $values);
            } catch (UniqueConstraintViolationException $e) {
                $form['name']->addError('Žánr s tímto jménem již existuje.');
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

        $form->addGroup('Žánry'); // následující prvky spadají do této skupiny

        foreach ($this->genreManager->getGenreValuePairs() as $id => $name) {
            $form->addInteger($id, $name);
        }

        $form->addGroup(null); // pro vyrenderovani button pod fieldset

        $form->addSubmit('save', 'Uložit nastavení');

        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
            try {
                foreach ($form->getGroups()['Žánry']->getControls() as $control) {
                    $this->genreManager->updateLiteratureSetGenreSetting($values->literature_sets_id, $control->name, $control->value);
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
