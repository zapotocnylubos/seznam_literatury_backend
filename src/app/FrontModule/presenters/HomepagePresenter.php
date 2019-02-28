<?php

namespace App\FrontModule\Presenters;


use Nette\Application\Responses\RedirectResponse;

final class HomepagePresenter extends BasePresenter
{
    public function actionDefault()
    {
        $this->sendResponse(new RedirectResponse('/literature_set/'));
    }
}
