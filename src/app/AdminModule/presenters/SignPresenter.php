<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Forms;
use Nette\Application\UI\Form;


final class SignPresenter extends BasePresenter
{
    /**
     * @var Forms\SignInFormFactory
     */
    private $signInFactory;

    public function __construct(Forms\SignInFormFactory $signInFactory)
    {
        parent::__construct();
        $this->signInFactory = $signInFactory;
    }

    public function actionIn()
    {
        if ($this->user->loggedIn) {
            $this->redirect('Homepage:');
        }
    }


    /**
     * Sign-in form factory.
     * @return Form
     */
    protected function createComponentSignInForm()
    {
        return $this->signInFactory->create(function () {
            $this->redirect('Homepage:');
        });
    }

    public function actionOut()
    {
        $this->user->logout();
    }
}
