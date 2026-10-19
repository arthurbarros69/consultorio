<?php
include(__DIR__ . '/../includes/cabecalho.php');
include(__DIR__ . '/../includes/menu.php');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Paciente.php';

$pacientes = Paciente::listar();
?>
<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0">Pacientes</h1>
        <a href="/consultorio/view/paciente/cadastrar.php" class="btn btn-primary">Novo Paciente</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Nascimento</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Endereço</th>
                    <th>Convênio</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($pacientes)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Nenhum paciente cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pacientes as $p): ?>
                    <?php $p = (array) $p; ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['cpf'] ?? '') ?></td>
                        <td>
                            <?php
                            $data = $p['data_nascimento'] ?? '';
                            echo $data ? date('d/m/Y', strtotime($data)) : '';
                            ?>
                        </td>
                        <td><?= htmlspecialchars($p['telefone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['endereco'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['convenio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['observacao'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pb-5"></div>
</main>

<?php include(__DIR__ . '/../includes/rodape.php'); ?>