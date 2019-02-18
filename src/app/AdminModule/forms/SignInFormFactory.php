<?php

namespace App\AdminModule\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('username', 'Přihlašovací jméno:')
			->setRequired('Zadejte prosím příhlašovací jméno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadejte prosím příhlašovací heslo.');

		$form->addCheckbox('remember', 'Zapamatovat si mě');

		$form->addSubmit('send', 'Přihlásit se');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('Neplatné uživatelské jméno nebo heslo.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
