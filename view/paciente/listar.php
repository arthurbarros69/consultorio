<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Paciente.php';

$pacientes = Paciente::listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pacientes</title>
    <style>
        body  { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        a.novo { display: inline-block; margin-bottom: 16px; }
    </style>
</head>
<body>
    <h2>Pacientes</h2>
    <p><a class="novo" href="/consultorio/view/paciente/cadastrar.php">+ Novo paciente</a></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Nascimento</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Convênio</th>
        </tr>
        <?php if (empty($pacientes)): ?>
            <tr><td colspan="7">Nenhum paciente cadastrado.</td></tr>
        <?php else: ?>
            <?php foreach ($pacientes as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['cpf']) ?></td>
                    <td><?= htmlspecialchars($p['data_nascimento']) ?></td>
                    <td><?= htmlspecialchars($p['telefone'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['convenio'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>