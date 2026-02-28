<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('admin/login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $this->redirect('/dashboard');
        } else {
            $this->view('admin/login', ['error' => 'E-mail ou senha inválidos.']);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}
