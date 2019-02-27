<?php

namespace App\AdminModule\Presenters;


use App\AdminModule\Forms\GenreFormFactory;
use App\Models\GenreManager;

final class GenrePresenter extends BasePresenter
{
    /** @var GenreFormFactory */
    private $genreFactory;

    /**  @var GenreManager */
    private $genreManager;

    public function __construct(GenreFormFactory $genreFactory, GenreManager $genreManager)
    {
        parent::__construct();
        $this->genreFactory = $genreFactory;
        $this->genreManager = $genreManager;
    }

    public function actionUpdate($id)
    {
        $genre = $this->genreManager->getGenre($id);
        $this['genreUpdateForm']->setDefaults($genre);
    }

    public function actionLiteratureSetSettings($literatureSetId) {
        $this['literatureSetSettingsForm']->setDefaults(['literature_sets_id' => $literatureSetId]);
//        dump($this->genreManager->getLiteratureSetGenresSettings($literatureSetId)->fetchAll());
//        exit();

        foreach($this->genreManager->getLiteratureSetGenresSettings($literatureSetId) as $genresSetting) {
            $this['literatureSetSettingsForm']->setDefaults([$genresSetting->genres_id => $genresSetting->min_count]);
        }
    }

    public function renderList()
    {
        $this->template->genres = $this->genreManager->getGenres();
    }

    public function handleDelete($id)
    {
        $this->genreManager->deleteGenre($id);
        $this->flashMessage('Žánr byl smazán.');
        $this->redirect('this');
    }

    public function createComponentGenreCreateForm()
    {
        return $this->genreFactory->create(function () {
            $this->flashMessage('Žánr byl vytvořen.');
            $this->redirect('Genre:list');
        });
    }

    public function createComponentGenreUpdateForm()
    {
        return $this->genreFactory->update(function () {
            $this->flashMessage('Žánr byl upraven.');
            $this->redirect('Genre:list');
        });
    }

    public function createComponentLiteratureSetSettingsForm()
    {
        return $this->genreFactory->literatureSetSettings(function ($literature_set_id) {
            $this->flashMessage('Nastavení bylo uloženo.');
            $this->redirect('LiteratureSet:detail', $literature_set_id);
        });
    }
}
