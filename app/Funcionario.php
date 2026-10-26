<?php

use App\DataBase;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Pessoa.php';

class Funcionario extends Pessoa
{
    public $cargo;
    public $cro;
    public $salario;
    public $data_admissao;
    public $ativo;

    public function cadastrar()
    {
        $db = new DataBase('funcionario');

        $this->id = $db->insert([
            'nome'            => $this->nome,
            'cpf'             => $this->cpf,
            'data_nascimento' => $this->data_nascimento,
            'telefone'        => $this->telefone,
            'email'           => $this->email,
            'cargo'           => $this->cargo,
            'cro'             => $this->cro ?: null,
            'salario'         => $this->salario,
            'data_admissao'   => $this->data_admissao,
            'ativo'           => $this->ativo,
        ]);

        return true;
    }

    public function alterar()
    {
        $db = new DataBase('funcionario');

        $db->execute(
            'update funcionario
                set nome = ?, cpf = ?, data_nascimento = ?, telefone = ?, email = ?,
                    cargo = ?, cro = ?, salario = ?, data_admissao = ?, ativo = ?
              where id = ?',
            [
                $this->nome,
                $this->cpf,
                $this->data_nascimento,
                $this->telefone,
                $this->email,
                $this->cargo,
                $this->cro ?: null,
                $this->salario,
                $this->data_admissao,
                $this->ativo,
                $this->id,
            ]
        );

        return true;
    }

    public function alterarStatus()
    {
        $db = new DataBase('funcionario');
        $db->execute('update funcionario set ativo = 1 - ativo where id = ?', [$this->id]);
    }

    public function excluir()
    {
        $db = new DataBase('funcionario');
        $db->execute('delete from funcionario where id = ?', [$this->id]);
    }

    public static function buscar($id)
    {
        $db = new DataBase('funcionario');
        return $db->execute('select * from funcionario where id = ?', [$id])->fetch(PDO::FETCH_ASSOC);
    }

    public static function listar()
    {
        $db = new DataBase('funcionario');
        return $db->select()->fetchAll(PDO::FETCH_ASSOC);
    }
}
