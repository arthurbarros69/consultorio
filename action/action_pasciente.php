<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('../vendor/autoload.php');

$action = $_GET['action'] ?? '';
$paciente = new Paciente(); // ajuste para o nome/namespace real da sua classe

switch ($action) {
    case 'cadastrar':
        $paciente->nome            = $_POST['nome'];
        $paciente->cpf             = $_POST['cpf'];
        $paciente->data_nascimento = $_POST['data_nascimento'];
        $paciente->email           = $_POST['email'];
        $paciente->endereco        = $_POST['endereco'];
        $paciente->convenio        = $_POST['convenio'];
        $paciente->observacao      = $_POST['observacao'];
        $paciente->cadastrar();

        header('Location: /consultorio/view/paciente/listar.php');
        exit;

    case 'alterar':
        break;

    case 'excluir':
        break;
}