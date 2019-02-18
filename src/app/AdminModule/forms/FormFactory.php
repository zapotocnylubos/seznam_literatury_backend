<?php

namespace App\AdminModule\Forms;

use Czubehead\BootstrapForms\BootstrapForm;
use Nette;


final class FormFactory
{
    use Nette\SmartObject;

    /**
     * @return BootstrapForm
     */
    public function create()
    {
        $form = new BootstrapForm;
        return $form;
    }
}
