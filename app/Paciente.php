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
 
    public static function listar() {
        return [];
    }
}
