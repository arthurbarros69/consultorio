<?php

use App\DataBase;

class Consulta
{
    // Valores aceitos pelo ENUM da coluna forma_pagamento
    const FORMAS = ['DINHEIRO', 'PIX', 'CARTÃO DE DEBITO', 'CARTÃO DE CREDITO'];

    public $id;
    public $paciente_id;
    public $dentista_id;
    public $data_hora;
    public $valor_total;
    public $valor_pago;
    public $forma_pagamento;
    public $procedimentos_realizados;
    public $procedimentos_arealizar;
    public $descricao;
    public $medicacao;

    public function cadastrar()
    {
        $db = new DataBase('consulta');

        $this->id = $db->insert([
            'paciente_id'              => $this->paciente_id,
            'dentista_id'              => $this->dentista_id,
            'data_hora'                => $this->data_hora,
            'valor_total'              => $this->valor_total,
            'valor_pago'               => $this->valor_pago,
            'forma_pagamento'          => $this->forma_pagamento,
            'procedimentos_realizados' => $this->procedimentos_realizados,
            'procedimentos_arealizar'  => $this->procedimentos_arealizar,
            'descricao'                => $this->descricao,
            'medicacao'                => $this->medicacao,
        ]);

        return true;
    }

    // Busca uma consulta pelo id
    public static function buscar($id)
    {
        foreach (self::listar() as $c) {
            $c = (array) $c;
            if ((int)($c['id'] ?? 0) === (int)$id) {
                return $c;
            }
        }
        return null;
    }

    // Atualiza a consulta no banco
    public static function atualizar($id, $dados)
    {
        $db = new DataBase('consulta');

        return $db->update('id = ' . (int)$id, [
            'paciente_id'              => $dados['paciente_id'],
            'dentista_id'              => $dados['dentista_id'],
            'data_hora'                => $dados['data_hora'],
            'valor_total'              => $dados['valor_total'],
            'valor_pago'               => $dados['valor_pago'],
            'forma_pagamento'          => $dados['forma_pagamento'],
            'procedimentos_realizados' => $dados['procedimentos_realizados'],
            'procedimentos_arealizar'  => $dados['procedimentos_arealizar'],
            'descricao'                => $dados['descricao'],
            'medicacao'                => $dados['medicacao'],
        ]);
    }

    // Exclui a consulta do banco
    public static function excluir($id)
    {
        $db = new DataBase('consulta');

        return $db->delete('id = ' . (int)$id);
    }

    public static function listar()
    {
        $db = new DataBase('consulta');
        return $db->select('data_hora DESC');
    }

    // Todos os funcionários (usado para mostrar o nome do dentista na listagem)
    public static function funcionarios()
    {
        $db = new DataBase('funcionario');
        return $db->select('nome ASC');
    }

    // Somente os funcionários com perfil de dentista (usado no formulário)
    public static function dentistas()
    {
        $lista = [];
        foreach (self::funcionarios() as $f) {
            $f = (array) $f;
            if (($f['perfil'] ?? '') === 'dentista') {
                $lista[] = $f;
            }
        }
        return $lista;
    }
}