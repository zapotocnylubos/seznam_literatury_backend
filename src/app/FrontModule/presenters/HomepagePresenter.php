<?php

namespace App\FrontModule\Presenters;


use Nette\Application\Responses\RedirectResponse;

final class HomepagePresenter extends BasePresenter
{
    public function actionDefault()
    {
        // Redirect to built React directory
        $this->sendResponse(new RedirectResponse('/literature_set/'));
    }
}
