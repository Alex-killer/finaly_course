<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use Delight\Auth\InvalidEmailException;

class RegisterController extends BaseController
{



    public function register()
    {
        echo $this->getTemplates()->render('page_register');
    }

    public function signUp()
    {
        try {
            $userId = $this->getAuth()->register($_POST['email'], $_POST['password'], $_POST['username']

//                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
//                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
//                echo '  For SMS, consider using a third-party service and a compatible SDK';
            );
            flash()->success('We have signed up a new user with the ID' . $userId);
            header('Location: /');
        }
        catch (InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header('Location: /register');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header('Location: /register');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header('Location: /register');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
            header('Location: /register');
        }

    }
}