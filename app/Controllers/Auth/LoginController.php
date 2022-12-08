<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use Delight\Auth\Auth;
use League\Plates\Engine;

class LoginController extends BaseController
{
    public function login()
    {
        echo $this->getTemplates()->render('page_login');
    }

    public function signIn()
    {
        try {
            $this->getAuth()->login($_POST['email'], $_POST['password']);
            flash()->success('User is logged in');
            header('Location: /users');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
            header('Location: /');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
            header('Location: /');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
            header('Location: /');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /');
        }
    }
}