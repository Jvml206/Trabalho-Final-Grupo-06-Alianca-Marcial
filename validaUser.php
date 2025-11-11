<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$usuario = new Usuario;

if ($usuario->sessaoExpirou()) {
    header("Location: login.php?session_expired=true");
    exit;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['tipo_usuario'])) {
    header("Location: login.php?error=not_logged_in"); 
    exit;
}

if(isset($nivelPermitido)){
    $tipoUsuario = $_SESSION['tipo_usuario'];

    if (!in_array($tipoUsuario, $nivelPermitido)) {
        switch ($tipoUsuario) {
            case 'Usuário':
                $redirect = 'index.php';
                break;
            default:
                $redirect = 'dashboard.php';
        }

        echo "<script>
                alert('Você não tem permissão para acessar esta página.');
                window.location.href='{$redirect}';
              </script>";
        exit;
    }
}