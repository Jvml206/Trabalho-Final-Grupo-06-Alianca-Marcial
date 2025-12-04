<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });

    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $nova_senha = filter_input(INPUT_POST, 'nova_senha', FILTER_SANITIZE_STRING);
    $confirmar_senha = filter_input(INPUT_POST, 'confirmar_senha', FILTER_SANITIZE_STRING);

    if ($nova_senha !== $confirmar_senha) {
        $mensagem = "<script>window.alert('As senhas não conferem!');</script>";

    } else {
        $usuario = new Usuario;
        $usuario->redefinirSenha($token, $nova_senha);
        $mensagem = "<script>window.alert('Senha alterada com sucesso.'); window.location.href='login.php';</script>";
    }

    echo $mensagem;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/login.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Redefinir Senha</title>
</head>

<body>

    <?php require_once "_parts/_navSite.php"; ?>

    <main class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="form-login">
            <p class="tituloh1">Redefinir Senha</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

                <div class="mb-3 col-12">
                    <label for="nova_senha" class="form-label">Nova Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="nova_senha" placeholder="Nova Senha"
                            name="nova_senha" required>
                        <button type="button" id="toggleSenha" class="btn-ver">
                            <i id="iconNovaSenha" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <p id="forcaSenha" class="fw-bold"></p>
                    <ol id="requisitos" class="mb-3 mt-3">
                        <li id="minChar" class="invalido">Mínimo 10 caracteres</li>
                        <li id="maiuscula" class="invalido">Pelo menos uma letra maiúscula</li>
                        <li id="minuscula" class="invalido">Pelo menos uma letra minúscula</li>
                        <li id="numero" class="invalido">Pelo menos um número</li>
                        <li id="especial" class="invalido">Pelo menos um caractere especial (!@#$%^&*...)</li>
                    </ol>
                </div>

                <div class="mb-3 col-12">
                    <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirmar_senha" placeholder="Confirmar Senha"
                            name="confirmar_senha" required>
                        <button type="button" id="toggleConfirmar" class="btn-ver">
                            <i id="iconConfirmarSenha" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small id="msgConfirmacao"></small>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn-salvar" name="btnSalvar" id="btnSalvar">Redefinir
                        Senha</button>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer mt-auto">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="JS/senha.js"></script>
    <!-- Botão do VLibras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <!-- Script do VLibras -->
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>