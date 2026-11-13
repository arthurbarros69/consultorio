<?php
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/consulta.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    Consulta::excluir($id);
}

header('Location: /consultorio/view/consulta/listar.php');
exit;