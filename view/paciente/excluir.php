<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Paciente.php';

// Só aceita exclusão vinda do formulário (POST)
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    Paciente::excluir($id);
}

// Volta para a lista (ajuste se o seu arquivo da lista não for listar.php)
header('Location: /consultorio/view/paciente/listar.php');
exit;