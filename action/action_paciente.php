<?php
// Mostra erros na tela enquanto você desenvolve (remova depois)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Paciente.php';

$action   = $_GET['action'] ?? '';
$paciente = new Paciente();

switch ($action) {
    case 'cadastrar':
        $paciente->nome            = $_POST['nome']            ?? '';
        $paciente->cpf             = $_POST['cpf']             ?? '';
        $paciente->data_nascimento = $_POST['data_nascimento'] ?? '';
        $paciente->telefone        = $_POST['telefone']        ?? '';
        $paciente->email           = $_POST['email']           ?? '';
        $paciente->endereco        = $_POST['endereco']        ?? '';
        $paciente->convenio        = $_POST['convenio']        ?? '';
        $paciente->observacao      = $_POST['observacao']      ?? '';

        $paciente->cadastrar();

        // Não pode ter nenhum echo antes do header
        header('Location: /consultorio/view/paciente/listar.php');
        exit;

    case 'alterar':
        break;

    case 'excluir':
        break;

    default:
        echo 'Ação inválida.';
}