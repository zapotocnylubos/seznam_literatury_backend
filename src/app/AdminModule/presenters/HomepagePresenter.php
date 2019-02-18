<?php

namespace App\AdminModule\Presenters;


final class HomepagePresenter extends BasePresenter
{
    public function renderDefault()
    {
        $this->template->anyVariable = 'any value';
    }
}
