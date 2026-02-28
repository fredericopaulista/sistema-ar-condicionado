<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Middlewares\AuthMiddleware;

class ClienteController extends BaseController
{
    private $customerModel;

    public function __construct()
    {
        AuthMiddleware::check();
        $this->customerModel = new Customer();
    }

    public function index()
    {
        $clientes = $this->customerModel->all();
        $this->view('admin/customers/index', [
            'title' => 'Gerenciar Clientes',
            'clientes' => $clientes
        ]);
    }

    public function create()
    {
        $this->view('admin/customers/create', [
            'title' => 'Novo Cliente'
        ]);
    }

    public function store()
    {
        $this->customerModel->create($_POST);
        $this->redirect('/clientes');
    }

    public function edit($id)
    {
        $cliente = $this->customerModel->find($id);
        $this->view('admin/customers/edit', [
            'title' => 'Editar Cliente',
            'cliente' => $cliente
        ]);
    }

    public function update($id)
    {
        $this->customerModel->update($id, $_POST);
        $this->redirect('/clientes');
    }

    public function delete($id)
    {
        $this->customerModel->delete($id);
        $this->redirect('/clientes');
    }
}
