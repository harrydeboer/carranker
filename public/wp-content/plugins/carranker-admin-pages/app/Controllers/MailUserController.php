<?php

declare(strict_types=1);

namespace CarrankerAdmin\App;

use CarrankerAdmin\App\Forms\MailUserForm;
use CarrankerAdmin\App\Forms\MailUserFormPasswordUpdate;
use CarrankerAdmin\App\Forms\MailUserFormUpdate;
use CarrankerAdmin\App\Models\MailUser;

class MailUserController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
	    require_once dirname(__DIR__) . '/Models/MailUser.php';
        require_once dirname(__DIR__) . '/Forms/MailUserForm.php';
        require_once dirname(__DIR__) . '/Forms/MailUserFormUpdate.php';
        require_once dirname(__DIR__) . '/Forms/MailUserFormPasswordUpdate.php';
    }

    public function view(array $urlParams)
    {
        $createOrUpdate = 'create';
        $formObject = null;
	    $this->set('mailUsers', MailUser::all());
        $this->set('form', new MailUserForm($createOrUpdate, $formObject));
    }

    public function create(array $urlParams, object $request)
    {
	    $form = new MailUserForm('create', $request);

	    if ($form->validate($request)) {
		    $mailUser = new MailUser($request);
		    $mailUser->setPassword($this->encryptPasswordSHA512($mailUser->getPassword()));
		    $mailUser->create();
	    }

	    $this->set('mailUsers', MailUser::all());
	    $this->set('form', $form);
        $this->_template->_action = 'view';
    }

	public function update(array $urlParams, $request)
	{
		$form = new MailUserFormUpdate('update', $request);

		if ($form->validate($request)) {
			$mailUser = MailUser::getById((int) $request->id);
			$mailUser->setDomain($request->domain);
			$mailUser->setEmail($request->email);
			$mailUser->setForward($request->forward);
			$mailUser->update();
		}

		$this->set('mailUsers', MailUser::all());
		$this->set('form', $form);
		$this->_template->_action = 'view';
	}

    public function updatePassword(array $urlParams, object $request)
    {
	    $form = new MailUserFormPasswordUpdate('update', $request);

	    if ($form->validate($request)) {
		    $mailUser = MailUser::getById((int) $request->id);
		    $mailUser->setPassword($this->encryptPasswordSHA512($request->password));
		    $mailUser->update();
		    $this->set('mailUsers', MailUser::all());
	    } else {
		    $this->set('mailUsers', MailUser::all());
	    }

        $this->_template->_action = 'view';
    }

    public function delete(array $urlParams, object $request)
    {
	    $mailUser = MailUser::findByEmail($request->deleteMailUserEmail);
	    if (!is_null($mailUser)) {
		    MailUser::delete($mailUser->getId());
		    $this->set('mailUsers', MailUser::all());
	    } else {
		    $this->set('mailUsers', MailUser::all());
	    }
	    $this->set('form', new MailUserForm('create'));
        $this->_template->_action = 'view';
    }

    private function encryptPasswordSHA512(string $password):string
    {
	    $saltlength = 50;

	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $salt = '';
	    for ($i = 0; $i < $saltlength; $i++) {
		    $salt .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return crypt( $password, '$6$' . $salt );
    }
}