<?php
require_once __DIR__ . '/../classes/Paciente.php';
 
include(__DIR__ . '/../includes/cabecalho.php');
include(__DIR__ . '/../includes/menu.php');
 
$pacientes = Paciente::listar();
?>
 
<main class="container mt-4">
    <h1>Lista de Pacientes</h1>
 
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Data de Nascimento</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Endereço</th>
                <th>Convênio</th>
                <th>Observação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($pacientes)): ?>
            <tr>
                <td colspan="9" class="text-center">Nenhum paciente cadastrado.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($pacientes as $paciente): ?>
            <tr>
                <td><?= htmlspecialchars($paciente->nome) ?></td>
                <td><?= htmlspecialchars($paciente->cpf) ?></td>
                <td><?= htmlspecialchars($paciente->data_nascimento) ?></td>
                <td><?= htmlspecialchars($paciente->telefone) ?></td>
                <td><?= htmlspecialchars($paciente->email) ?></td>
                <td><?= htmlspecialchars($paciente->endereco) ?></td>
                <td><?= htmlspecialchars($paciente->convenio) ?></td>
                <td><?= htmlspecialchars($paciente->observacao) ?></td>
                <td>
                    <a class="btn btn-sm btn-warning" href="editar.php?id=<?= $paciente->id ?>">Editar</a>
                    <a class="btn btn-sm btn-danger"
                       href="/consultorio/action/action_paciente.php?action=excluir&id=<?= $paciente->id ?>"
                       onclick="return confirm('Deseja excluir este paciente?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
 
    <div class="pb-5"></div>
</main>
 
<?php include(__DIR__ . '/../includes/rodape.php'); ?>
