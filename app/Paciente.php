<?php

use App\DataBase;

require_once __DIR__ . '/Pessoa.php';

class Paciente extends Pessoa
{
    public $convenio;
    public $observacao;

    public function cadastrar()
    {
        $db = new DataBase('paciente');

        $this->id = $db->insert([
            'nome'            => $this->nome,
            'cpf'             => $this->cpf,
            'data_nascimento' => $this->data_nascimento,
            'telefone'        => $this->telefone,
            'email'           => $this->email,
            'endereco'        => $this->endereco,
            'convenio'        => $this->convenio,
            'observacao'      => $this->observacao,
        ]);

        return true;
    }

    public static function alterar()
    {
    }

    public function excluir()
    {
    }

    public static function listar()
    {
        $db = new DataBase('paciente');
        return $db->select();
    }
}