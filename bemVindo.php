<?php require_once 'validaUser.php';
$idUsuario = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['tipo_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/bemVindo.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Aliança Marcial</title>
</head>

<body>

    <?php if (isset($_SESSION['tipo_usuario'])):
        $tipoUsuario = $_SESSION['tipo_usuario'];

        if ($tipoUsuario != 'Administrador'):
            require_once "_parts/_navSite.php";
        else:
            require_once "_parts/_navAdmin.php";
        endif;
    endif; ?>

    <main class="mainBemVindo">
        <div class="bemVindo">
            <h1 class="tituloh1">Bem Vindo!</h1>
            <h1 class="tituloh1">Você está logado</h1>
        </div>
        <?php if ($tipoUsuario == 'Atleta' && empty($Atleta->search('fk_id_usuario', $idUsuario))):?>
                <p class="p-conta">Termine de criar sua conta para ter acesso aos serviços</p>
                <a href="atleta.php" class="btn btn-voltar">Clique aqui</a>
        <?php elseif ($tipoUsuario == 'Instrutor' && empty($Instrutor->search('fk_id_usuario', $idUsuario))): ?>
            <p class="p-conta">Termine de criar sua conta para ter acesso aos serviços</p>
            <a href="instrutor.php" class="btn btn-voltar">Clique aqui</a>
        <?php endif ?>
    </main>
    <footer>
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
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