<?php

namespace App\Controllers;

use App\Models\User;
use App\Middlewares\AuthMiddleware;

class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        AuthMiddleware::permission('usuarios.manage');
        $this->userModel = new User();
    }

    public function index()
    {
        $users = $this->userModel->allWithRole();
        $this->view('admin/users/index', [
            'title' => 'Gerenciar Usuários',
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = $this->userModel->getRoles();
        $this->view('admin/users/create', [
            'title' => 'Novo Usuário',
            'roles' => $roles
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'],
                'role_id' => $_POST['role_id']
            ];

            if ($this->userModel->create($data)) {
                $this->redirect('/usuarios');
            }
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        $roles = $this->userModel->getRoles();
        
        if (!$user) {
            $this->redirect('/usuarios');
        }

        $this->view('admin/users/edit', [
            'title' => 'Editar Usuário',
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'] ?? '',
                'role_id' => $_POST['role_id']
            ];

            if ($this->userModel->update($id, $data)) {
                $this->redirect('/usuarios');
            }
        }
    }

    public function delete($id)
    {
        // Don't allow deleting self
        if ($id == $_SESSION['user_id']) {
            $this->redirect('/usuarios?error=self_delete');
        }

        if ($this->userModel->delete($id)) {
            $this->redirect('/usuarios');
        }
    }
}
