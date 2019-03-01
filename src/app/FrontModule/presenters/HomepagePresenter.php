<?php

namespace App\FrontModule\Presenters;


use Nette\Application\Responses\RedirectResponse;
use Tracy\Debugger;

final class HomepagePresenter extends BasePresenter
{
    public function actionDefault()
    {
        // Redirect to built React directory
        $this->sendResponse(new RedirectResponse($this->getHttpRequest()->getUrl()->getHostUrl() . '/formular/'));
    }
}
