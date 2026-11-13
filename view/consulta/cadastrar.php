<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/consulta.php';
require_once __DIR__ . '/../../app/Paciente.php';

$inc = __DIR__ . '/../includes';

$pacientes = Paciente::listar();
$dentistas = Consulta::dentistas();
$todas     = Consulta::listar();
$consulta  = null;
$acao      = 'cadastrar';

include $inc . '/cabecalho.php';
include $inc . '/menu.php';
?>

<main class="container mt-4 mb-5 pb-5">
    <h1 class="mb-4">Cadastrar Consulta</h1>

    <?php include __DIR__ . '/form.php'; ?>
</main>

<?php include $inc . '/rodape.php'; ?>