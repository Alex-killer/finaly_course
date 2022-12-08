<?php

namespace App\Controllers;

class UserController extends BaseController
{

    public function getAllUsers()
    {
        $userId = $this->getAuth()->getUserId();
        $userAdmin = $this->doseUserHaveAdmin();
        $users = $this->getQb()->getAll('users');
        $paginate = $this->getQb()->paginate('users');
        $userPaginate = $this->getQb()->getItemPaginate();

        echo $this->getTemplates()->render('users', ['userPaginate' => $userPaginate, 'userAdmin' => $userAdmin, 'userId' => $userId, 'paginate' => $paginate]);
    }

    public function doseUserHaveAdmin()
    {
        if ($this->getAuth()->hasRole(\Delight\Auth\Role::ADMIN)) {
            return true;
        }
    }

    public function addUser()
    {
        if ($this->doseUserHaveAdmin()) {
            echo $this->getTemplates()->render('create_user');
        } else {
            flash()->error('Error');
            header('Location: /users');
        }

    }

    public function createUser()
    {
        try {
            $userId = $this->getAuth()->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
            flash()->success('We have signed up a new user with the ID' . $userId);
            header('Location: /users');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header('Location: /add_user');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header('Location: /add_user');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header('Location: /add_user');
        }
    }

    public function editUser($vars)
    {
        $id = $vars['id'];
        $user = $this->getQb()->getData($id, 'users');
        $username = $user['username'];
        echo $this->getTemplates()->render('edit', ['username' => $username, 'id' => $id]);
    }

    public function updateUser()
    {
        $id = $_POST['id'];
        $data = $_POST;
        $this->getQb()->update($id, $data, 'users');
        header('Location: /users');
    }

    public function editStatus($vars)
    {
        $id = $vars['id'];
        echo $this->getTemplates()->render('status', ['id' => $id]);
    }

    public function updateStatus()
    {
        $id = $_POST['id'];
        $data = $_POST;
        $this->getQb()->update($id, $data, 'users');
        flash()->success('Status update');
        header('Location: /users');
    }

    public function editAvatar($vars)
    {
        $id = $vars['id'];
        $user = $this->getQb()->getData($id, 'users');
        $user_avatar = $user['image'];
        echo $this->getTemplates()->render('media', ['id' => $id, 'user_avatar' => $user_avatar]);
    }

    public function updateAvatar()
    {
        $id = $_POST['id'];
        $image = $_FILES['image']['name'];
        $data = ['image' => $image];
        $fileTmpName = $_FILES['image']['tmp_name'];

        if (move_uploaded_file($fileTmpName, 'upload/' . $_FILES['image']['name'])) {
            $this->getQb()->update($id, $data, 'users');
            flash()->success('File upload');
            header('Location: /users');
        } else {
            flash()->error('File error');
            header('Location: /users');
        }
    }

    public function editPassword($vars)
    {
        $id = $vars['id'];
        $user = $this->getQb()->getData($id, 'users');
        $email = $user['email'];
        echo $this->getTemplates()->render('security', ['email' => $email, 'id' => $id]);
    }

    public function updatePassword()
    {
        if ($_POST['newPassword'] == $_POST['confirmPassword']) {
            try {
                $this->getAuth()->admin()->changePasswordForUserById($_POST['id'], $_POST['newPassword']);
                flash()->success('Password update');
                header('Location: /users');
            }
            catch (\Delight\Auth\UnknownIdException $e) {
                die('Unknown ID');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                die('Invalid password');
            }
        } else {
            flash()->error('Passwords do not match');
            header('Location: /edit_password/' . $_POST['id']);
        }
    }

    public function deleteUser($vars)
    {
        $userAdmin = $this->doseUserHaveAdmin();

        try {
            $this->getAuth()->admin()->deleteUserById($vars['id']);
            flash()->error('User delete');
            if ($userAdmin) {
                header('Location: /users');
            } else
                header('Location: /');
        }
        catch (\Delight\Auth\UnknownIdException $e) {
            flash()->error('Unknown ID');
            header('Location: /users');
        }
    }

    public function profile($vars)
    {
        $id = $vars['id'];
        $user = $this->getQb()->getData($id, 'users');
        echo $this->getTemplates()->render('page_profile', ['user' => $user]);
    }
}