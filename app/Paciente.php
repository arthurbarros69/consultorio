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

    // Busca um paciente pelo id (usa o listar() que você já tem)
    public static function buscar($id)
    {
        foreach (self::listar() as $p) {
            $p = (array) $p;
            if ((int)($p['id'] ?? 0) === (int)$id) {
                return $p;
            }
        }
        return null;
    }

    // Atualiza o paciente no banco
    public static function atualizar($id, $dados)
    {
        $db = new DataBase('paciente');

        return $db->update('id = ' . (int)$id, [
            'nome'            => $dados['nome'],
            'cpf'             => $dados['cpf'],
            'data_nascimento' => $dados['data_nascimento'],
            'telefone'        => $dados['telefone'],
            'email'           => $dados['email'],
            'endereco'        => $dados['endereco'],
            'convenio'        => $dados['convenio'],
            'observacao'      => $dados['observacao'],
        ]);
    }

    // Exclui o paciente do banco
    public static function excluir($id)
    {
        $db = new DataBase('paciente');

        return $db->delete('id = ' . (int)$id);
    }

    public static function listar()
    {
        $db = new DataBase('paciente');
        return $db->select('nome ASC');
    }
}