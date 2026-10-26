<?php
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

        try {
            $paciente->cadastrar();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo '<script>alert("Já existe um paciente com esse CPF."); history.back();</script>';
                exit;
            }
            throw $e;
        }

        header('Location: /consultorio/view/paciente/listar.php');
        exit;

    case 'alterar':
        break;

    case 'excluir':
        break;

    default:
        echo 'Ação inválida.';
}