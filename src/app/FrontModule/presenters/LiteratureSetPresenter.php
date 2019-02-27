<?php

namespace App\FrontModule\Presenters;


use App\Models\LiteratureSetManager;

final class LiteratureSetPresenter extends BasePresenter
{
    /**  @var LiteratureSetManager */
    private $literatureSetManager;

    public function __construct(LiteratureSetManager $literatureManager)
    {
        parent::__construct();
        $this->literatureSetManager = $literatureManager;
    }

    public function actionActiveLiteratureSet()
    {
        $currentLiteratureSet = $this->literatureSetManager->getActiveLiteratureSet();

        if (!$currentLiteratureSet) {
            $this->sendJson(null);
        }

        $literatureGroups = [];
        foreach ($currentLiteratureSet->related('literature_groups')->order('sort_order', 'DESC') as $literatureGroupRow) {
            $literatureGroup = [
                'title' => $literatureGroupRow->title,
                'min_count' => $literatureGroupRow->min_count,
                'books' => []
            ];

            foreach ($literatureGroupRow->related('books')->order('sort_order', 'DESC') as $literatureGroupsHasBooksRow) {
                $book = [
                    'title' => $literatureGroupsHasBooksRow->book->title,
                    'author_id' => $literatureGroupsHasBooksRow->book->author_id,
                    'author' => $literatureGroupsHasBooksRow->book->author->full_name,
                    'literature_form_id' => $literatureGroupsHasBooksRow->book->literature_form_id,
                    'literature_form' => $literatureGroupsHasBooksRow->book->literature_form->name
                ];
                $literatureGroup['books'][] = $book;
            }

            $literatureGroups[] = $literatureGroup;
        }

        $requiredLiteratureForms = [];
        foreach ($currentLiteratureSet->related('literature_forms') as $literatureSetsRequiredLiteratureForm) {
            $requiredLiteratureForms[] = [
                'literature_form_id' => $literatureSetsRequiredLiteratureForm->literature_forms_id,
                'literature_form' => $literatureSetsRequiredLiteratureForm->literature_form->name,
                'min_count' => $literatureSetsRequiredLiteratureForm->min_count
            ];
        }

        $data = [
            'period' => $currentLiteratureSet->period,
            'required_book_count' => $currentLiteratureSet->required_book_count,
            'author_max_count' => $currentLiteratureSet->author_max_count,
            'groups' => $literatureGroups,
            'required_literature_forms' => $requiredLiteratureForms
        ];

        $this->sendJson($data);
    }
}
