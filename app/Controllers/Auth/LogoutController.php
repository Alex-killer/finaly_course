<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class LogoutController extends BaseController
{
    public function logout()
    {
        $this->getAuth()->logOut();

// or

        try {
            $this->getAuth()->logOutEverywhereElse();
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            header('Location: /');
            die('Not logged in');
        }

// or

        try {
            $this->getAuth()->logOutEverywhere();
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
    }
}