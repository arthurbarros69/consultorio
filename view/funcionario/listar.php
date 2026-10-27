<?php

use App\Funcionario;

include('../includes/cabecalho.php');
include('../includes/menu.php');
include('../includes/rodape.php');
$funcionarios = Funcionario::listar();
?>
<main class="container">
    <h1>Lista de Funcionarios</h1>
    <table border="3">
        <thead>
            <tr>
                <th>Nome</th>
                <br>
                <th>CPF</th>
                <br>
                <th>Data</th>
                <br>
                <th>Telefone</th>
                <th>Email</th>
                <th>Endereco</th>
                <th>CRO</th>
                <th>Especialidade</th>
                <th>Senha</th>
                <th>Perfil</th>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($funcionarios as $funcionario) {
                echo "<tr>
        <td>" . $funcionario->nome . "</td>
        <td>" . $funcionario->cpf . "</td>
        <td>" . $funcionario->data_nascimento . "</td>
        <td>" . $funcionario->telefone . "</td>
        <td>" . $funcionario->email . "</td>
        <td>" . $funcionario->endereco . "</td>
        <td>" . $funcionario->cro . "</td>
        <td>" . $funcionario->especialidade . "</td>
         <td>" . $funcionario->senha . "</td>
          <td>" . $funcionario->perfil . "</td>
        <td>
        <a href='editar.php?id=" . $funcionario->id . "'>
        <a href='/consultorio/action/action_funcionario.php?
        action=excluir&id=" . $funcionario->id . "'>
        </td>
        </tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="pb-5"></div>
</main>