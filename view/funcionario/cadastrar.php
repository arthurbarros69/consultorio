<?php
include('../includes/cabecalho.php');
include('../includes/menu.php');
include('../includes/rodape.php');
?>
<main class="container">
    <h1>Cadastrar funcionario</h1>
    <form method="POST" action="/consultorio/action/action_funcionario.php?action=cadastrar">
        Nome * : <input name="nome" type="text" class="form-control" required>
        CPF * : <input name="cpf" type="text" class="form-control" required>
        Data de Nascimento *: <input name="data_nascimento" type="date" class="form-control" required>
        Telefone *: <input name="telefone" type="text" class="form-control" required>
        Email * : <input name="email" type="email" class="form-control" required>
        Endereço * : <input name="endereco" type="text" class="form-control" required>
        CRO * : <input name="cro" type="text" class="form-control" required>
        Especialidade* : <input name="especialidade" type="text" class="form-control" required>
        Senha*: <input name="senha" type="text" class="form-contol" required>
        <br>
        Perfil*: <input name="perfil" type="text" class="form-control" required>
        <br>
        <input type="submit" value="Cadastrar funcionario" class="btn btn-primary">
    </form>
    <div class="pb-5"></div>
</main>