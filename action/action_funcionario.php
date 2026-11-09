<?php
require_once __DIR__ . '/../app/Funcionario.php';

$action      = $_GET['action'] ?? '';
$funcionario = new Funcionario();

// Campos do formulário (usados no cadastrar e no alterar)
$funcionario->id              = (int)($_POST['id'] ?? 0);
$funcionario->nome            = $_POST['nome']            ?? '';
$funcionario->cpf             = $_POST['cpf']             ?? '';
$funcionario->data_nascimento = $_POST['data_nascimento'] ?? '';
$funcionario->telefone        = $_POST['telefone']        ?? '';
$funcionario->email           = $_POST['email']           ?? '';
$funcionario->cargo           = $_POST['cargo']           ?? '';
$funcionario->cro             = $_POST['cro']             ?? '';
$funcionario->salario         = $_POST['salario']         ?? 0;
$funcionario->data_admissao   = $_POST['data_admissao']   ?? '';
$funcionario->ativo           = isset($_POST['ativo']) ? 1 : 0; // checkbox só é enviado quando marcado

switch ($action) {
    case 'cadastrar':
        $funcionario->cadastrar();
        // Não pode ter nenhum echo antes do header
        header('Location: /consultorio/view/funcionario/listar.php?msg=' . urlencode('Funcionário cadastrado com sucesso.'));
        exit;

    case 'alterar':
        $funcionario->alterar();
        header('Location: /consultorio/view/funcionario/listar.php?msg=' . urlencode('Funcionário alterado com sucesso.'));
        exit;

    case 'status':
        $funcionario->alterarStatus();
        header('Location: /consultorio/view/funcionario/listar.php');
        exit;

    case 'excluir':
        $funcionario->excluir();
        header('Location: /consultorio/view/funcionario/listar.php?msg=' . urlencode('Funcionário excluído.'));
        exit;

    default:
        echo 'Ação inválida.';
}
 