<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Paciente.php';

$pacientes = Paciente::listar();

// Ordem alfabética pelo nome, ignorando maiúsculas e acentos
function chaveNome($s) {
    return strtr(mb_strtolower((string)$s, 'UTF-8'), [
        'á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a',
        'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
        'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
        'ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
        'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
        'ç'=>'c',
    ]);
}

usort($pacientes, function ($a, $b) {
    $a = (array) $a;
    $b = (array) $b;

    return strcmp(
        chaveNome($a['nome'] ?? ''),
        chaveNome($b['nome'] ?? '')
    );
});

include __DIR__ . '/../includes/cabecalho.php';
include __DIR__ . '/../includes/menu.php';
?>

<main class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0">Pacientes</h1>

        <a href="/consultorio/view/paciente/cadastrar.php"
           class="btn btn-primary">
            + Novo Paciente
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle"
               style="
                   --bs-table-striped-bg: rgba(13,110,253,.07);
                   --bs-table-hover-bg: rgba(13,110,253,.15);
               ">

            <thead style="
                --bs-table-bg: #0d6efd;
                --bs-table-color: #fff;
                --bs-table-border-color: #0b5ed7;
            ">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Nascimento</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Convênio</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($pacientes)): ?>

                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Nenhum paciente cadastrado.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($pacientes as $p): ?>
                        <?php $p = (array) $p; ?>

                        <tr>
                            <td><?= htmlspecialchars((string)($p['id'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($p['nome'] ?? '') ?></td>
                            <td><?= htmlspecialchars($p['cpf'] ?? '') ?></td>

                            <td>
                                <?php
                                $data = $p['data_nascimento'] ?? '';
                                if ($data && strtotime($data) !== false) {
                                    echo date('d/m/Y', strtotime($data));
                                }
                                ?>
                            </td>

                            <td><?= htmlspecialchars($p['telefone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($p['convenio'] ?? '') ?></td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">

                                    <!-- Botão Editar: abre em nova aba -->
                                    <a href="/consultorio/view/paciente/editar.php?id=<?= urlencode((string)($p['id'] ?? '')) ?>"
                                       target="_blank" rel="noopener"
                                       class="btn btn-primary btn-sm"
                                       style="min-width: 95px;">
                                        <i class="bi bi-pencil-square"></i>
                                        Editar
                                    </a>

                                    <!-- Botão Excluir: apaga do banco -->
                                    <form method="post"
                                          action="/consultorio/view/paciente/excluir.php"
                                          class="m-0"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este paciente?');">
                                        <input type="hidden" name="id"
                                               value="<?= htmlspecialchars((string)($p['id'] ?? '')) ?>">
                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                style="min-width: 95px;">
                                            <i class="bi bi-trash"></i>
                                            Excluir
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>
            </tbody>

        </table>
    </div>

    <div class="pb-5"></div>

</main>

<script>
// Garante o Bootstrap JS caso o rodapé não carregue
window.addEventListener('load', () => {
    if (!window.bootstrap) {
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
        document.head.appendChild(s);
    }
});
</script>

<?php include __DIR__ . '/../includes/rodape.php'; ?> 