<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Paciente.php';

$urlLista = '/consultorio/view/paciente/listar.php'; // ajuste se o nome for outro

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . $urlLista);
    exit;
}

// Quando clica em Salvar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Paciente::atualizar($id, [
        'nome'            => trim($_POST['nome'] ?? ''),
        'cpf'             => trim($_POST['cpf'] ?? ''),
        'data_nascimento' => $_POST['data_nascimento'] ?: null,
        'telefone'        => trim($_POST['telefone'] ?? ''),
        'email'           => trim($_POST['email'] ?? ''),
        'endereco'        => trim($_POST['endereco'] ?? ''),
        'convenio'        => trim($_POST['convenio'] ?? ''),
        'observacao'      => trim($_POST['observacao'] ?? ''),
    ]);

    header('Location: ' . $urlLista);
    exit;
}

// Quando só abre a página
$p = Paciente::buscar($id);

if (!$p) {
    header('Location: ' . $urlLista);
    exit;
}

include __DIR__ . '/../includes/cabecalho.php';
include __DIR__ . '/../includes/menu.php';
?>

<main class="container mt-4">

    <h1 class="mb-4">Editar Paciente</h1>

    <form method="post" class="row g-3">
        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">

        <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?= htmlspecialchars($p['nome'] ?? '') ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">CPF</label>
            <input type="text" name="cpf" class="form-control"
                   value="<?= htmlspecialchars($p['cpf'] ?? '') ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Nascimento</label>
            <input type="date" name="data_nascimento" class="form-control"
                   value="<?= htmlspecialchars($p['data_nascimento'] ?? '') ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control"
                   value="<?= htmlspecialchars($p['telefone'] ?? '') ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control"
                   value="<?= htmlspecialchars($p['email'] ?? '') ?>">
        </div>

        <div class="col-md-8">
            <label class="form-label">Endereço</label>
            <input type="text" name="endereco" class="form-control"
                   value="<?= htmlspecialchars($p['endereco'] ?? '') ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Convênio</label>
            <input type="text" name="convenio" class="form-control"
                   value="<?= htmlspecialchars($p['convenio'] ?? '') ?>">
        </div>

        <div class="col-12">
            <label class="form-label">Observação</label>
            <textarea name="observacao" rows="3" class="form-control"><?= htmlspecialchars($p['observacao'] ?? '') ?></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= $urlLista ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <div class="pb-5"></div>

</main>

<?php include __DIR__ . '/../includes/rodape.php'; ?> 