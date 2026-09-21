<?php
require_once __DIR__ . '/Pessoa.php';
 
class Funcionario extends Pessoa {
    public $cro;
    public $especialidade;
    public $senha;
    public $perfil;
 
    public function cadastrar() {
    }
 
    public static function alterar() {
    }
 
    public function excluir() {
    }
 
    public static function listar() {
        return [];
    }
}
