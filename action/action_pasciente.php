<?php
require('../vendor/autoload.php');
$action = $_GET['action'];
$paciente = new $paciente();
switch($action)
{
      case 'cadastrar':
        $paciente->nome = $_POST['nome'];
        $paciente->cpf = $_POST['cpf'];
        $paciente->data_nascimento = $_POST['data_nascimento'];
        $paciente->email = $_POST['email'];
        $paciente->endereco = $_POST['endereco'];
        $paciente->convenio = $_POST['convenio'];
        $paciente->observacao = $_POST['observacao'];
        $paciente->cadastrar();
        echo"<pre>";
        print_r($paciente);
        echo"</prev>";
       // header(location: /consulorio/view/paciente/listar.php)
        break;
      case 'alterar':
        break;
      case 'excluir':
        break;

}

?>