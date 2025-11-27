<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Aliança Marcial</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <?php
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });
    $Atleta = new Atleta();
    $Academia = new Academia();
    $PedidoAjuda = new PedidoAjuda();
    ?>
    <main>
        <h1 class="text-center texto-pedAju">Atletas que precisam de ajuda</h1>
        <div class="atleta-box">
            <?php
            $pedidoAjuda = $PedidoAjuda->all();
            foreach ($pedidoAjuda as $pa):
                $id = intval($pa->id_pedido_ajuda);
                $idA = intval($pa->fk_id_atleta);
                $atleta = $Atleta->search('id_atleta', $idA);
                $academia = $Academia->search('id_academia', $atleta->fk_id_academia);
                ?>
                <div class="cardPedido d-flex align-items-center flex-column">
                    <div class="card-img-container-pedido">
                        <img src="Images/pedidoDeAjuda/<?php echo $pa->imagem; ?>">
                    </div>
                    <p class="nomeAtleta"><?php echo $atleta->nome_atleta ?></p>
                    <p class="motivoAtleta"><?php echo $pa->titulo ?></p>
                    <p class="academiaAtleta">Academia: <?php echo $academia->nome_fantasia ?></p>
                    <a href="pedidoAtleta.php?id=<?php echo $id ?>" class="btn btn-atleta">Ver mais</a>
                </div>
            <?php endforeach; ?>
        </div>
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