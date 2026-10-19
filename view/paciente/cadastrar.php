<?php
include(__DIR__ . '/../includes/cabecalho.php');
include(__DIR__ . '/../includes/menu.php');
?>
<main class="container mt-4">
<h1>Cadastrar Paciente</h1>
<form method="POST" action="/consultorio/action/action_paciente.php?action=cadastrar">
<label class="form-label">Nome *:</label>
<input name="nome" type="text" class="form-control" maxlength="150" required>

<label class="form-label mt-2">CPF *:</label>
<input name="cpf" id="cpf" type="text" class="form-control" maxlength="14"
       placeholder="000.000.000-00" inputmode="numeric" required>

<label class="form-label mt-2">Data de Nascimento *:</label>
<input name="data_nascimento" type="date" class="form-control" required>

<label class="form-label mt-2">Telefone *:</label>
<input name="telefone" id="telefone" type="text" class="form-control" maxlength="15"
       placeholder="(00) 00000-0000" inputmode="numeric" required>
<small id="ddd-info" class="form-text text-muted"></small>

<label class="form-label mt-2">Email *:</label>
<input name="email" type="email" class="form-control" maxlength="150" required>

<label class="form-label mt-2">Endereço *:</label>
<input name="endereco" type="text" class="form-control" maxlength="255" required>

<label class="form-label mt-2">Convênio *:</label>
<input name="convenio" type="text" class="form-control" maxlength="100" required>

<label class="form-label mt-2">Observação *:</label>
<input name="observacao" type="text" class="form-control" maxlength="255" required>

<br>
<input type="submit" value="Cadastrar" class="btn btn-primary">
</form>
<div class="pb-5"></div>
</main>

<script>
// ---------- Máscara do CPF: só números, formata 000.000.000-00 ----------
document.getElementById('cpf').addEventListener('input', function (e) {
    let v = e.target.value.replace(/\D/g, '');      // tira tudo que não é número
    v = v.slice(0, 11);                              // limita a 11 dígitos
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = v;
});

// ---------- Máscara do telefone: (DDD) 00000-0000 ----------
document.getElementById('telefone').addEventListener('input', function (e) {
    let v = e.target.value.replace(/\D/g, '');
    v = v.slice(0, 11);
    if (v.length > 10) {
        // celular: (00) 00000-0000
        v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    } else if (v.length > 6) {
        // fixo: (00) 0000-0000
        v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    } else if (v.length > 2) {
        v = v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
    } else if (v.length > 0) {
        v = v.replace(/(\d{0,2})/, '($1');
    }
    e.target.value = v;
    mostrarEstadoDoDDD(v);
});

// ---------- Mostra o estado do DDD digitado ----------
const DDDS = {
    '11': 'SP', '12': 'SP', '13': 'SP', '14': 'SP', '15': 'SP', '16': 'SP', '17': 'SP', '18': 'SP', '19': 'SP',
    '21': 'RJ', '22': 'RJ', '24': 'RJ',
    '27': 'ES', '28': 'ES',
    '31': 'MG', '32': 'MG', '33': 'MG', '34': 'MG', '35': 'MG', '37': 'MG', '38': 'MG',
    '41': 'PR', '42': 'PR', '43': 'PR', '44': 'PR', '45': 'PR', '46': 'PR',
    '47': 'SC', '48': 'SC', '49': 'SC',
    '51': 'RS', '53': 'RS', '54': 'RS', '55': 'RS',
    '61': 'DF', '62': 'GO', '64': 'GO', '63': 'TO',
    '65': 'MT', '66': 'MT', '67': 'MS',
    '68': 'AC', '69': 'RO',
    '71': 'BA', '73': 'BA', '74': 'BA', '75': 'BA', '77': 'BA',
    '79': 'SE', '81': 'PE', '87': 'PE',
    '82': 'AL', '83': 'PB', '84': 'RN', '85': 'CE', '88': 'CE',
    '86': 'PI', '89': 'PI',
    '91': 'PA', '93': 'PA', '94': 'PA',
    '92': 'AM', '97': 'AM',
    '95': 'RR', '96': 'AP', '98': 'MA', '99': 'MA',
};

const NOMES_ESTADO = {
    SP: 'São Paulo', RJ: 'Rio de Janeiro', ES: 'Espírito Santo', MG: 'Minas Gerais',
    PR: 'Paraná', SC: 'Santa Catarina', RS: 'Rio Grande do Sul', DF: 'Distrito Federal',
    GO: 'Goiás', TO: 'Tocantins', MT: 'Mato Grosso', MS: 'Mato Grosso do Sul',
    AC: 'Acre', RO: 'Rondônia', BA: 'Bahia', SE: 'Sergipe', PE: 'Pernambuco',
    AL: 'Alagoas', PB: 'Paraíba', RN: 'Rio Grande do Norte', CE: 'Ceará',
    PI: 'Piauí', MA: 'Maranhão', PA: 'Pará', AM: 'Amazonas', RR: 'Roraima', AP: 'Amapá',
};

function mostrarEstadoDoDDD(valorFormatado) {
    const digitos = valorFormatado.replace(/\D/g, '');
    const info = document.getElementById('ddd-info');
    if (digitos.length >= 2) {
        const ddd = digitos.slice(0, 2);
        const uf = DDDS[ddd];
        info.textContent = uf ? `DDD ${ddd} — ${NOMES_ESTADO[uf]} (${uf})` : `DDD ${ddd} não encontrado`;
    } else {
        info.textContent = '';
    }
}
</script>

<?php include(__DIR__ . '/../includes/rodape.php'); ?>