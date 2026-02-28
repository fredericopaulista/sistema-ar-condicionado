<?php

namespace App\Models;

class Customer extends BaseModel
{
    protected $table = 'clientes';

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nome, email, telefone, cpf_cnpj, endereco, numero, complemento, bairro, cep, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nome'],
            $data['email'],
            $data['telefone'],
            $data['cpf_cnpj'],
            $data['endereco'],
            $data['numero'],
            $data['complemento'] ?? null,
            $data['bairro'],
            $data['cep'],
            $data['cidade'],
            $data['estado']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nome = ?, email = ?, telefone = ?, cpf_cnpj = ?, endereco = ?, numero = ?, complemento = ?, bairro = ?, cep = ?, cidade = ?, estado = ? WHERE id = ?");
        return $stmt->execute([
            $data['nome'],
            $data['email'],
            $data['telefone'],
            $data['cpf_cnpj'],
            $data['endereco'],
            $data['numero'],
            $data['complemento'] ?? null,
            $data['bairro'],
            $data['cep'],
            $data['cidade'],
            $data['estado'],
            $id
        ]);
    }
}
