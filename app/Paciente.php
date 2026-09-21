<?php
require_once __DIR__ . '/Pessoa.php';
 
class Paciente extends Pessoa {
    public $convenio;
    public $observacao;
 
    public function cadastrar() {
    }
 
    public static function alterar() {
    }
 
    public function excluir() {
    }
 
    // Deve devolver SEMPRE um array (mesmo vazio), para o foreach do listar.php não quebrar.
    // Quando você ligar o banco de dados, é aqui que entra o SELECT.
    public static function listar() {
        return [];
    }
}
