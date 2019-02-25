<?php

namespace App\AdminModule\Presenters;

use Nette;


/**
 * Base presenter for whole module.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected function startup()
    {
        parent::startup();

        if (!$this->user->loggedIn) {
            if (!$this->isLinkCurrent('Sign:in')) {
                $this->redirect('Sign:in');
            }
        }
    }
}
