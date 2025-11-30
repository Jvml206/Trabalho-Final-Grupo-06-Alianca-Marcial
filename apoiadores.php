<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Apoiadores</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <?php
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });
    $InstituicaoApoiadora = new InstituicaoApoiadora();
    ?>
    <main>
        <h1 class="tituloh1">Instituições que nos apoiam</h1>

        <?php
        $instituicaoApoiadora = $InstituicaoApoiadora->all();
        foreach ($instituicaoApoiadora as $ia):
            $id = intval($ia->id_instituicao_apoiadora);
            ?>
            <div class="cardinstituicao d-flex align-items-center flex-column">
                <a href="<?php echo $ia->link; ?>" target="_blank"> <img src="Images/instituicao_apoiadora/<?php echo $ia->logo; ?>" title="<?php echo $ia->nome_fantasia; ?>" 
                alt="<?php echo $ia->nome_fantasia; ?>" class="foto-instituicao"></a>
            </div>
        <?php endforeach; ?>
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