<?php
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['tipo_usuario']) {
        case 'Administrador':
            header('Location: dashboard.php');
            break;
        case 'Instrutor':
            header('Location: index.php');
            break;
        case 'Atleta':
            header('Location: index.php');
            break;
        default:
            header('Location: index.php');
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Login</title>
</head>
<?php
if (filter_has_var(INPUT_POST, "logar")) {
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });
    $usuario = new Usuario;
    $usuario->setEmail(filter_input(INPUT_POST, 'email'));
    $usuario->setSenha(filter_input(INPUT_POST, 'senha'));
    $mensagem = $usuario->login();
    echo "<script>alert('$mensagem');</script>";
}
?>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="form-login">
            <p>Login</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row">
                <input type="hidden" name="redirect"
                    value="<?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'dashboard.php'; ?>">
                <div class="mb-3 col-12">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" placeholder="E-mail" name="email">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" placeholder="Senha" name="senha">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn " name="logar">Entrar</button>
                </div>
                <div class="mt-3">
                    <a href="redefinir_senha.php">Esqueceu a senha?</a>
                </div>
                <div class="mt-3">
                    <a href="criar_conta.php">Criar conta</a>
                </div>
            </form>
        </div>
</div>
    <footer class="footer mt-auto">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>