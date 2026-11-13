<?php
// Formulário usado por cadastrar.php e editar.php
// Variáveis esperadas: $pacientes, $dentistas, $todas (todas as consultas), $consulta (array ou null), $acao
$c = $consulta ?? [];

$v = function ($campo, $padrao = '') use ($c) {
    return htmlspecialchars((string)($c[$campo] ?? $padrao));
};

// datetime-local usa "2026-11-13T14:30"
$dataHora = !empty($c['data_hora']) ? str_replace(' ', 'T', substr($c['data_hora'], 0, 16)) : '';

$procedimentos = ['Avaliação', 'Limpeza', 'Restauração', 'Canal', 'Extração', 'Clareamento', 'Aparelho'];

// Agenda usada para avisar sobre conflito de horário
$agenda = [];
foreach ($todas as $t) {
    $t = (array) $t;
    $agenda[] = [
        'id'          => (int)$t['id'],
        'data_hora'   => str_replace(' ', 'T', substr($t['data_hora'], 0, 16)),
        'dentista_id' => (int)$t['dentista_id'],
    ];
}
?>

<form id="formConsulta" class="needs-validation" novalidate
      action="/consultorio/action/action_consulta.php?action=<?= $acao ?>" method="post">

    <?php if (!empty($c['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="paciente_id" class="form-label">Paciente</label>
            <select name="paciente_id" id="paciente_id" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($pacientes as $p): ?>
                    <?php $p = (array) $p; ?>
                    <option value="<?= (int)$p['id'] ?>"
                            data-telefone="<?= htmlspecialchars($p['telefone'] ?? '') ?>"
                            data-convenio="<?= htmlspecialchars($p['convenio'] ?? '') ?>"
                            <?= (int)($c['paciente_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Selecione o paciente.</div>
        </div>

        <div class="col-md-6">
            <label for="dentista_id" class="form-label">Dentista</label>
            <select name="dentista_id" id="dentista_id" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($dentistas as $f): ?>
                    <option value="<?= (int)$f['id'] ?>"
                            <?= (int)($c['dentista_id'] ?? 0) === (int)$f['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['nome']) ?>
                        <?= !empty($f['especialidade']) ? ' — ' . htmlspecialchars($f['especialidade']) : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Selecione o dentista.</div>
        </div>

        <div class="col-12 d-none" id="infoPaciente">
            <div class="alert alert-light border mb-0 py-2">
                <strong>Telefone:</strong> <span id="infoTel"></span> &nbsp;|&nbsp;
                <strong>Convênio:</strong> <span id="infoConv"></span>
            </div>
        </div>

        <div class="col-md-6">
            <label for="data_hora" class="form-label">Data e hora</label>
            <input type="datetime-local" name="data_hora" id="data_hora" class="form-control"
                   value="<?= $dataHora ?>" required>
            <div class="invalid-feedback">Informe a data e a hora.</div>
        </div>

        <div class="col-md-6">
            <label for="forma_pagamento" class="form-label">Forma de pagamento</label>
            <select name="forma_pagamento" id="forma_pagamento" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach (Consulta::FORMAS as $forma): ?>
                    <option value="<?= $forma ?>" <?= ($c['forma_pagamento'] ?? '') === $forma ? 'selected' : '' ?>><?= $forma ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Selecione a forma de pagamento.</div>
        </div>

        <div class="col-12 d-none" id="avisoConflito">
            <div class="alert alert-warning mb-0 py-2">
                Atenção: este dentista já tem uma consulta nesse mesmo dia e horário.
            </div>
        </div>

        <div class="col-md-4">
            <label for="valor_total" class="form-label">Valor total</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" min="0" name="valor_total" id="valor_total"
                       class="form-control" value="<?= $v('valor_total', '0.00') ?>">
            </div>
        </div>

        <div class="col-md-4">
            <label for="valor_pago" class="form-label">Valor pago</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" min="0" name="valor_pago" id="valor_pago"
                       class="form-control" value="<?= $v('valor_pago', '0.00') ?>">
                <button type="button" class="btn btn-outline-secondary" id="btnQuitar">Pago total</button>
                <div class="invalid-feedback">O valor pago não pode ser maior que o total.</div>
            </div>
        </div>

        <div class="col-md-4">
            <label for="restante" class="form-label">Restante a pagar</label>
            <input type="text" id="restante" class="form-control" value="R$ 0,00" readonly>
        </div>

        <div class="col-md-6">
            <label for="procedimentos_realizados" class="form-label">Procedimentos realizados</label>
            <input type="text" name="procedimentos_realizados" id="procedimentos_realizados"
                   class="form-control" maxlength="255" value="<?= $v('procedimentos_realizados') ?>">
            <div class="mt-2">
                <?php foreach ($procedimentos as $proc): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 mb-1 chip"
                            data-alvo="procedimentos_realizados" data-proc="<?= $proc ?>"><?= $proc ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-6">
            <label for="procedimentos_arealizar" class="form-label">Procedimentos a realizar</label>
            <input type="text" name="procedimentos_arealizar" id="procedimentos_arealizar"
                   class="form-control" maxlength="255" value="<?= $v('procedimentos_arealizar') ?>">
            <div class="mt-2">
                <?php foreach ($procedimentos as $proc): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1 mb-1 chip"
                            data-alvo="procedimentos_arealizar" data-proc="<?= $proc ?>"><?= $proc ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-6">
            <label for="descricao" class="form-label">
                Descrição <small class="text-muted">(<span id="cDescricao">0</span>/255)</small>
            </label>
            <textarea name="descricao" id="descricao" rows="3" maxlength="255"
                      class="form-control contar" data-contador="cDescricao"><?= $v('descricao') ?></textarea>
        </div>

        <div class="col-md-6">
            <label for="medicacao" class="form-label">
                Medicação <small class="text-muted">(<span id="cMedicacao">0</span>/255)</small>
            </label>
            <textarea name="medicacao" id="medicacao" rows="3" maxlength="255"
                      class="form-control contar" data-contador="cMedicacao"><?= $v('medicacao') ?></textarea>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/consultorio/view/consulta/listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<script>
(function () {
    var form     = document.getElementById('formConsulta');
    var selPac   = document.getElementById('paciente_id');
    var selDent  = document.getElementById('dentista_id');
    var info     = document.getElementById('infoPaciente');
    var dataHora = document.getElementById('data_hora');
    var aviso    = document.getElementById('avisoConflito');
    var total    = document.getElementById('valor_total');
    var pago     = document.getElementById('valor_pago');
    var restante = document.getElementById('restante');
    var agenda   = <?= json_encode($agenda, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
    var idAtual  = <?= (int)($c['id'] ?? 0) ?>;

    // Em uma consulta nova, já preenche com a data e hora de agora
    if (!dataHora.value) {
        var n = new Date();
        n.setMinutes(n.getMinutes() - n.getTimezoneOffset());
        dataHora.value = n.toISOString().slice(0, 16);
    }

    // Mostra telefone e convênio do paciente escolhido
    function atualizarPaciente() {
        var op = selPac.options[selPac.selectedIndex];
        if (!selPac.value) { info.classList.add('d-none'); return; }
        document.getElementById('infoTel').textContent  = op.dataset.telefone || 'não informado';
        document.getElementById('infoConv').textContent = op.dataset.convenio || 'particular';
        info.classList.remove('d-none');
    }

    // Avisa se o dentista já tem consulta no mesmo dia e horário
    function checarConflito() {
        var achou = false;
        if (dataHora.value && selDent.value) {
            achou = agenda.some(function (a) {
                return a.id !== idAtual &&
                       a.data_hora === dataHora.value &&
                       String(a.dentista_id) === selDent.value;
            });
        }
        aviso.classList.toggle('d-none', !achou);
    }

    // Calcula o restante e valida o valor pago
    function atualizarValores() {
        var t = parseFloat(total.value) || 0;
        var p = parseFloat(pago.value) || 0;
        var falta = Math.max(t - p, 0);
        restante.value = falta.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        pago.setCustomValidity(p > t ? 'Valor pago maior que o total' : '');
    }

    document.getElementById('btnQuitar').addEventListener('click', function () {
        pago.value = total.value || 0;
        atualizarValores();
    });

    // Botões de procedimentos rápidos: acrescentam ao campo indicado
    document.querySelectorAll('.chip').forEach(function (b) {
        b.addEventListener('click', function () {
            var campo = document.getElementById(b.dataset.alvo);
            var novo  = campo.value.trim() ? campo.value.trim() + ', ' + b.dataset.proc : b.dataset.proc;
            if (novo.length <= campo.maxLength) { campo.value = novo; }
            campo.focus();
        });
    });

    // Contadores de caracteres
    document.querySelectorAll('.contar').forEach(function (campo) {
        var alvo = document.getElementById(campo.dataset.contador);
        var atualizar = function () { alvo.textContent = campo.value.length; };
        campo.addEventListener('input', atualizar);
        atualizar();
    });

    // Validação do Bootstrap
    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
    });

    selPac.addEventListener('change', atualizarPaciente);
    selDent.addEventListener('change', checarConflito);
    dataHora.addEventListener('input', checarConflito);
    total.addEventListener('input', atualizarValores);
    pago.addEventListener('input', atualizarValores);

    atualizarPaciente();
    checarConflito();
    atualizarValores();
})();
</script>