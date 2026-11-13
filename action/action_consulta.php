<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/consulta.php';

$action = $_GET['action'] ?? '';

// Dados que vêm do formulário (iguais para cadastrar e alterar)
function dadosDoFormulario()
{
    // O campo datetime-local envia "2026-11-13T14:30"; o banco quer "2026-11-13 14:30:00"
    $dataHora = str_replace('T', ' ', $_POST['data_hora'] ?? '');
    if (strlen($dataHora) === 16) {
        $dataHora .= ':00';
    }

    return [
        'paciente_id'              => $_POST['paciente_id'] ?? '',
        'dentista_id'              => $_POST['dentista_id'] ?? '',
        'data_hora'                => $dataHora,
        'valor_total'              => ($_POST['valor_total'] ?? '') !== '' ? $_POST['valor_total'] : 0,
        'valor_pago'               => ($_POST['valor_pago']  ?? '') !== '' ? $_POST['valor_pago']  : 0,
        'forma_pagamento'          => $_POST['forma_pagamento'] ?? '',
        'procedimentos_realizados' => trim($_POST['procedimentos_realizados'] ?? ''),
        'procedimentos_arealizar'  => trim($_POST['procedimentos_arealizar']  ?? ''),
        'descricao'                => trim($_POST['descricao']  ?? ''),
        'medicacao'                => trim($_POST['medicacao']  ?? ''),
    ];
}

function voltarComErro($mensagem)
{
    echo '<script>alert(' . json_encode($mensagem, JSON_UNESCAPED_UNICODE) . '); history.back();</script>';
    exit;
}

switch ($action) {
    case 'cadastrar':
        $d = dadosDoFormulario();

        if (!in_array($d['forma_pagamento'], Consulta::FORMAS, true)) {
            voltarComErro('Selecione uma forma de pagamento válida.');
        }

        $consulta = new Consulta();
        $consulta->paciente_id              = $d['paciente_id'];
        $consulta->dentista_id              = $d['dentista_id'];
        $consulta->data_hora                = $d['data_hora'];
        $consulta->valor_total              = $d['valor_total'];
        $consulta->valor_pago               = $d['valor_pago'];
        $consulta->forma_pagamento          = $d['forma_pagamento'];
        $consulta->procedimentos_realizados = $d['procedimentos_realizados'];
        $consulta->procedimentos_arealizar  = $d['procedimentos_arealizar'];
        $consulta->descricao                = $d['descricao'];
        $consulta->medicacao                = $d['medicacao'];

        try {
            $consulta->cadastrar();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                voltarComErro('Paciente ou dentista inválido.');
            }
            throw $e;
        }

        header('Location: /consultorio/view/consulta/listar.php');
        exit;

    case 'alterar':
        $id = (int)($_POST['id'] ?? 0);
        $d  = dadosDoFormulario();

        if (!in_array($d['forma_pagamento'], Consulta::FORMAS, true)) {
            voltarComErro('Selecione uma forma de pagamento válida.');
        }

        try {
            Consulta::atualizar($id, $d);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                voltarComErro('Paciente ou dentista inválido.');
            }
            throw $e;
        }

        header('Location: /consultorio/view/consulta/listar.php');
        exit;

    default:
        echo 'Ação inválida.';
}