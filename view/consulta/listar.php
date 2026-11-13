<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/consulta.php';
require_once __DIR__ . '/../../app/Paciente.php';

$inc = __DIR__ . '/../includes';

// Mapas id => nome, para mostrar os nomes na tabela
$pacientes = [];
foreach (Paciente::listar() as $p) {
    $p = (array) $p;
    $pacientes[$p['id']] = $p['nome'];
}

$dentistas = [];
foreach (Consulta::funcionarios() as $f) {
    $f = (array) $f;
    $dentistas[$f['id']] = $f['nome'];
}

// Situação do pagamento, calculada a partir do valor total e do valor pago
function situacaoPagamento($total, $pago)
{
    if ($total <= 0 && $pago <= 0) {
        return 'Sem valor';
    }
    if ($pago >= $total) {
        return 'Pago';
    }
    return $pago > 0 ? 'Parcial' : 'Pendente';
}

$cores = [
    'Pago'      => 'bg-success',
    'Parcial'   => 'bg-warning text-dark',
    'Pendente'  => 'bg-danger',
    'Sem valor' => 'bg-secondary',
];

$consultas = Consulta::listar();

// Contadores dos cartões e dados para o modal de detalhes
$contagem = ['Pago' => 0, 'Parcial' => 0, 'Pendente' => 0];
$detalhes = [];
foreach ($consultas as $c) {
    $c     = (array) $c;
    $total = (float)$c['valor_total'];
    $pago  = (float)$c['valor_pago'];
    $sit   = situacaoPagamento($total, $pago);

    if (isset($contagem[$sit])) {
        $contagem[$sit]++;
    }

    $detalhes[$c['id']] = [
        'paciente'   => $pacientes[$c['paciente_id']] ?? 'Paciente removido',
        'dentista'   => $dentistas[$c['dentista_id']] ?? 'Dentista removido',
        'data_hora'  => $c['data_hora'],
        'forma'      => $c['forma_pagamento'],
        'total'      => $total,
        'pago'       => $pago,
        'realizados' => $c['procedimentos_realizados'] ?? '',
        'arealizar'  => $c['procedimentos_arealizar'] ?? '',
        'descricao'  => $c['descricao'] ?? '',
        'medicacao'  => $c['medicacao'] ?? '',
    ];
}

$rotulos = ['Pago' => 'Pagas', 'Parcial' => 'Parciais', 'Pendente' => 'Pendentes'];

include $inc . '/cabecalho.php';
include $inc . '/menu.php';
?>

<main class="container mt-4 mb-5 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="m-0">Consultas</h1>
        <a href="/consultorio/view/consulta/cadastrar.php" class="btn btn-primary">Nova consulta</a>
    </div>

    <!-- Cartões: clique para filtrar pela situação do pagamento -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center card-filtro border-primary" data-filtro="todas" role="button">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold"><?= count($consultas) ?></div>
                    <div>Todas</div>
                </div>
            </div>
        </div>
        <?php foreach ($contagem as $nomeSit => $qtd): ?>
            <div class="col-6 col-md-3">
                <div class="card text-center card-filtro" data-filtro="<?= $nomeSit ?>" role="button">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold"><?= $qtd ?></div>
                        <div><?= $rotulos[$nomeSit] ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-2 mb-3">
        <div class="col">
            <input type="search" id="busca" class="form-control"
                   placeholder="Buscar por paciente, dentista ou procedimento...">
        </div>
        <div class="col-auto">
            <button type="button" id="btnHoje" class="btn btn-outline-primary">Somente hoje</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th data-col="0">Paciente <span class="seta"></span></th>
                    <th data-col="1">Dentista <span class="seta"></span></th>
                    <th data-col="2">Data e hora <span class="seta"></span></th>
                    <th data-col="3">Procedimentos <span class="seta"></span></th>
                    <th data-col="4">Total <span class="seta"></span></th>
                    <th data-col="5">Pago <span class="seta"></span></th>
                    <th data-col="6">Situação <span class="seta"></span></th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody id="tabela">
                <?php foreach ($consultas as $c): ?>
                    <?php
                    $c     = (array) $c;
                    $nome  = $pacientes[$c['paciente_id']] ?? 'Paciente removido';
                    $dent  = $dentistas[$c['dentista_id']] ?? 'Dentista removido';
                    $total = (float)$c['valor_total'];
                    $pago  = (float)$c['valor_pago'];
                    $sit   = situacaoPagamento($total, $pago);
                    $proc  = trim($c['procedimentos_realizados'] ?? '') !== '' ? $c['procedimentos_realizados'] : '—';
                    $ts    = strtotime($c['data_hora']);
                    ?>
                    <tr class="linha"
                        data-situacao="<?= $sit ?>"
                        data-data="<?= date('Y-m-d', $ts) ?>"
                        data-total="<?= $total ?>"
                        data-pago="<?= $pago ?>"
                        data-restante="<?= max($total - $pago, 0) ?>">
                        <td data-v="<?= htmlspecialchars(mb_strtolower($nome)) ?>"><?= htmlspecialchars($nome) ?></td>
                        <td data-v="<?= htmlspecialchars(mb_strtolower($dent)) ?>"><?= htmlspecialchars($dent) ?></td>
                        <td data-v="<?= date('YmdHis', $ts) ?>">
                            <?= date('d/m/Y H:i', $ts) ?>
                            <span class="badge text-bg-primary ms-1 d-none badge-hoje">Hoje</span>
                        </td>
                        <td data-v="<?= htmlspecialchars(mb_strtolower($proc)) ?>">
                            <div class="text-truncate" style="max-width: 180px;"
                                 title="<?= htmlspecialchars($proc) ?>"><?= htmlspecialchars($proc) ?></div>
                        </td>
                        <td data-v="<?= $total ?>">R$ <?= number_format($total, 2, ',', '.') ?></td>
                        <td data-v="<?= $pago ?>">R$ <?= number_format($pago, 2, ',', '.') ?></td>
                        <td data-v="<?= $sit ?>">
                            <span class="badge <?= $cores[$sit] ?>"><?= $sit ?></span>
                        </td>
                        <td class="text-end text-nowrap">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-ver"
                                    data-id="<?= (int)$c['id'] ?>">Ver</button>
                            <a href="/consultorio/view/consulta/editar.php?id=<?= (int)$c['id'] ?>"
                               class="btn btn-sm btn-warning">Editar</a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#modalExcluir"
                                    data-id="<?= (int)$c['id'] ?>"
                                    data-nome="<?= htmlspecialchars($nome) ?>">Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr id="vazio" class="<?= empty($consultas) ? '' : 'd-none' ?>">
                    <td colspan="8" class="text-center">Nenhuma consulta encontrada.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-wrap justify-content-between text-muted gap-2">
        <span>Mostrando <strong id="qtdVisivel">0</strong> de <?= count($consultas) ?> consultas</span>
        <span>
            Total: <strong id="somaTotal">R$ 0,00</strong> &nbsp;|&nbsp;
            Recebido: <strong id="somaPago">R$ 0,00</strong> &nbsp;|&nbsp;
            A receber: <strong id="somaFalta">R$ 0,00</strong>
        </span>
    </div>
</main>

<!-- Modal de detalhes -->
<div class="modal fade" id="modalVer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0" id="detalhesLista"></dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir consulta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Deseja realmente excluir a consulta de <strong id="modalNome"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="modalConfirmar" href="#" class="btn btn-danger">Excluir</a>
            </div>
        </div>
    </div>
</div>

<style>
    .card-filtro { cursor: pointer; transition: box-shadow .15s, transform .15s; }
    .card-filtro:hover { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .15); transform: translateY(-2px); }
    thead th[data-col] { cursor: pointer; user-select: none; white-space: nowrap; }
</style>

<script>
(function () {
    var detalhes = <?= json_encode($detalhes, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;

    var tbody   = document.getElementById('tabela');
    var vazio   = document.getElementById('vazio');
    var linhas  = Array.from(tbody.querySelectorAll('tr.linha'));
    var busca   = document.getElementById('busca');
    var btnHoje = document.getElementById('btnHoje');
    var cards   = document.querySelectorAll('.card-filtro');

    var filtroSituacao = 'todas';
    var soHoje = false;
    var ordem = { col: null, asc: true };

    // Data de hoje no formato AAAA-MM-DD (do navegador)
    var d = new Date();
    var hoje = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');

    function dinheiro(n) {
        return Number(n).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }
    function dataHoraBR(s) {
        var partes = s.split(' ');
        var dt = partes[0].split('-');
        return dt[2] + '/' + dt[1] + '/' + dt[0] + ' às ' + partes[1].slice(0, 5);
    }

    // Aplica busca + situação + "somente hoje"
    function filtrar() {
        var termo = busca.value.toLowerCase();
        var visiveis = 0, total = 0, pago = 0, falta = 0;

        linhas.forEach(function (l) {
            var ok = (filtroSituacao === 'todas' || l.dataset.situacao === filtroSituacao) &&
                     (!soHoje || l.dataset.data === hoje) &&
                     l.textContent.toLowerCase().indexOf(termo) !== -1;
            l.style.display = ok ? '' : 'none';
            if (ok) {
                visiveis++;
                total += parseFloat(l.dataset.total) || 0;
                pago  += parseFloat(l.dataset.pago) || 0;
                falta += parseFloat(l.dataset.restante) || 0;
            }
        });

        vazio.classList.toggle('d-none', visiveis !== 0);
        document.getElementById('qtdVisivel').textContent = visiveis;
        document.getElementById('somaTotal').textContent = dinheiro(total);
        document.getElementById('somaPago').textContent  = dinheiro(pago);
        document.getElementById('somaFalta').textContent = dinheiro(falta);
    }

    // Ordenação ao clicar no título da coluna
    function ordenar() {
        var ehNumero = function (s) { return /^-?\d+(\.\d+)?$/.test(s); };

        linhas.sort(function (a, b) {
            var x = a.children[ordem.col].dataset.v;
            var y = b.children[ordem.col].dataset.v;
            var r = (ehNumero(x) && ehNumero(y)) ? (parseFloat(x) - parseFloat(y)) : x.localeCompare(y, 'pt-BR');
            return ordem.asc ? r : -r;
        });
        linhas.forEach(function (l) { tbody.insertBefore(l, vazio); });

        document.querySelectorAll('thead th[data-col]').forEach(function (th) {
            th.querySelector('.seta').textContent =
                (+th.dataset.col === ordem.col) ? (ordem.asc ? '▲' : '▼') : '';
        });
    }

    document.querySelectorAll('thead th[data-col]').forEach(function (th) {
        th.addEventListener('click', function () {
            var col = +th.dataset.col;
            ordem.asc = (ordem.col === col) ? !ordem.asc : true;
            ordem.col = col;
            ordenar();
        });
    });

    // Cartões de situação
    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            filtroSituacao = card.dataset.filtro;
            cards.forEach(function (c) { c.classList.remove('border-primary'); });
            card.classList.add('border-primary');
            filtrar();
        });
    });

    btnHoje.addEventListener('click', function () {
        soHoje = !soHoje;
        btnHoje.classList.toggle('active', soHoje);
        filtrar();
    });

    busca.addEventListener('input', filtrar);

    // Etiqueta "Hoje" nas consultas do dia
    linhas.forEach(function (l) {
        if (l.dataset.data === hoje) { l.querySelector('.badge-hoje').classList.remove('d-none'); }
    });

    // Modal de detalhes
    var modalVer = new bootstrap.Modal(document.getElementById('modalVer'));
    document.querySelectorAll('.btn-ver').forEach(function (b) {
        b.addEventListener('click', function () {
            var x = detalhes[b.dataset.id];
            var campos = [
                ['Paciente', x.paciente],
                ['Dentista', x.dentista],
                ['Data e hora', dataHoraBR(x.data_hora)],
                ['Pagamento', x.forma],
                ['Valor total', dinheiro(x.total)],
                ['Valor pago', dinheiro(x.pago)],
                ['Restante', dinheiro(Math.max(x.total - x.pago, 0))],
                ['Realizados', x.realizados || '—'],
                ['A realizar', x.arealizar || '—'],
                ['Descrição', x.descricao || '—'],
                ['Medicação', x.medicacao || '—']
            ];
            var dl = document.getElementById('detalhesLista');
            dl.innerHTML = '';
            campos.forEach(function (c) {
                var dt = document.createElement('dt'); dt.className = 'col-sm-4'; dt.textContent = c[0];
                var dd = document.createElement('dd'); dd.className = 'col-sm-8'; dd.textContent = c[1];
                dl.appendChild(dt); dl.appendChild(dd);
            });
            modalVer.show();
        });
    });

    // Modal de exclusão
    document.getElementById('modalExcluir').addEventListener('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        document.getElementById('modalNome').textContent = btn.getAttribute('data-nome');
        document.getElementById('modalConfirmar').href =
            '/consultorio/view/consulta/excluir.php?id=' + btn.getAttribute('data-id');
    });

    filtrar();
})();
</script>

<?php include $inc . '/rodape.php'; ?>