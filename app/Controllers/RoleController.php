<?php
namespace App\Controllers;

use App\Models\Role;
use App\Middlewares\AuthMiddleware;

class RoleController extends BaseController
{
    private $roleModel;

    public function __construct()
    {
        AuthMiddleware::permission('usuarios.manage');
        $this->roleModel = new Role();
    }

    public function index()
    {
        $roles = $this->roleModel->allWithPermissionCount();
        $this->view('admin/roles/index', [
            'title' => 'Níveis de Acesso',
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $permissions = $this->roleModel->getAllPermissions();
        $this->view('admin/roles/create', [
            'title' => 'Novo Nível de Acesso',
            'permissions' => $permissions
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'slug' => $_POST['slug'],
                'descricao' => $_POST['descricao']
            ];
            
            $permissionIds = $_POST['permissions'] ?? [];

            $roleId = $this->roleModel->create($data);
            if ($roleId) {
                $this->roleModel->syncPermissions($roleId, $permissionIds);
                $this->redirect('/roles');
            }
        }
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            $this->redirect('/roles');
        }

        $permissions = $this->roleModel->getAllPermissions();
        $this->view('admin/roles/edit', [
            'title' => 'Editar Nível de Acesso',
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'slug' => $_POST['slug'],
                'descricao' => $_POST['descricao']
            ];
            
            $permissionIds = $_POST['permissions'] ?? [];

            if ($this->roleModel->update($id, $data)) {
                $this->roleModel->syncPermissions($id, $permissionIds);
                $this->redirect('/roles');
            }
        }
    }

    public function delete($id)
    {
        // Don't allow deleting admin role if slug is admin
        $role = $this->roleModel->find($id);
        if ($role && $role['slug'] === 'admin') {
            $this->redirect('/roles?error=admin_delete');
        }

        if ($this->roleModel->delete($id)) {
            $this->redirect('/roles');
        }
    }
}
