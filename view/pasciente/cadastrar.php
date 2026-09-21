<?php
include(__DIR__ . '/../includes/cabecalho.php');
include(__DIR__ . '/../includes/menu.php');
?>
 
<main class="container mt-4">
    <h1>Cadastrar Paciente</h1>
 
    <form method="POST" action="/consultorio/action/action_paciente.php?action=cadastrar">
 
        <label class="form-label">Nome *:</label>
        <input name="nome" type="text" class="form-control" required>
 
        <label class="form-label mt-2">CPF *:</label>
        <input name="cpf" type="text" class="form-control" required>
 
        <label class="form-label mt-2">Data de Nascimento *:</label>
        <input name="data_nascimento" type="date" class="form-control" required>
 
        <label class="form-label mt-2">Telefone *:</label>
        <input name="telefone" type="text" class="form-control" required>
 
        <label class="form-label mt-2">Email *:</label>
        <input name="email" type="email" class="form-control" required>
 
        <label class="form-label mt-2">Endereço *:</label>
        <input name="endereco" type="text" class="form-control" required>
 
        <label class="form-label mt-2">Convênio *:</label>
        <input name="convenio" type="text" class="form-control" required>
 
        <label class="form-label mt-2">Observação *:</label>
        <input name="observacao" type="text" class="form-control" required>
 
        <br>
        <input type="submit" value="Cadastrar" class="btn btn-primary">
    </form>
 
    <div class="pb-5"></div>
</main>
 
<?php include(__DIR__ . '/../includes/rodape.php'); ?>
