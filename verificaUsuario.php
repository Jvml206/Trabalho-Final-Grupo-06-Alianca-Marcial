<?php
require_once 'Classes/Usuario.class.php';

$Usuario = new Usuario();

$nome = trim($_POST['nome_usuario'] ?? '');
if (empty($nome)) {
    echo 'nao_existe';
    exit;
}

$usuario = $Usuario->searchString('nome_usuario', $nome);
echo $usuario ? 'existe' : 'nao_existe';
