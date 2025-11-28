<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/index.css">
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
            $pedidoAjuda = $PedidoAjuda->allPedAju();
            foreach ($pedidoAjuda as $pa):
                $id = intval($pa->id_pedido_ajuda);
                $idA = intval($pa->fk_id_atleta);
                $atleta = $Atleta->search('id_atleta', $idA);
                $academia = $Academia->search('id_academia', $atleta->fk_id_academia);
                ?>
                <div
                    class="cardPedido d-flex align-items-center flex-column <?php echo ($pa->meta === 'atingida') ? 'atingida' : ''; ?>">
                    <div class="card-img-container-pedido">
                        <img src="Images/pedidoDeAjuda/<?php echo htmlspecialchars($pa->imagem); ?>" alt="imagem pedido">
                    </div>

                    <p class="nomeAtleta"><?php echo htmlspecialchars($atleta->nome_atleta); ?></p>
                    <p class="motivoAtleta"><?php echo htmlspecialchars($pa->titulo); ?></p>
                    <p class="academiaAtleta">Academia: <?php echo htmlspecialchars($academia->nome_fantasia); ?></p>
                    <button type="button" class="btn btn-success btn-antes" data-bs-toggle="modal"
                        data-bs-target="#pedidoModal<?php echo $id; ?>">
                        Ajudar
                    </button>

                    <?php if ($pa->meta === 'atingida'): ?>
                        <div class="overlay-atingida" aria-hidden="false">
                            <h4>Meta Atingida!</h4>
                            <p>Obrigado pela ajuda</p>
                            <button type="button" class="btn btn-light btn-ver-card" data-bs-toggle="modal"
                                data-bs-target="#pedidoModal<?php echo $id; ?>">
                                Ver
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal fade" id="pedidoModal<?php echo $id; ?>" tabindex="-1"
                    aria-labelledby="pedidoModalLabel<?php echo $id; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="pedidoModalLabel<?php echo $id; ?>">
                                    <?php echo $pa->titulo ?>
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="nomeAtleta">Atleta: <?php echo $atleta->nome_atleta ?></p>
                                <p class="esporteAtleta">Esporte: <?php echo $atleta->esporte ?></p>
                                <p class="nomeAcadAtleta">Academia: <?php echo $academia->nome_fantasia ?></p>
                                <p class="pixAtleta">Pix: <?php echo $pa->pix ?></p>
                                <div class="progress" role="progressbar" aria-label="Success example"
                                    aria-valuenow="<?php echo $pa->valor_atingido / $pa->valor_necessario * 100 ?>"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar text-bg-success"
                                        style="width: <?php echo ($pa->valor_atingido / $pa->valor_necessario) * 100 ?>%">
                                        <?php echo number_format(($pa->valor_atingido / $pa->valor_necessario) * 100, 0, ',', '.'); ?>
                                        %
                                    </div>
                                </div>
                                <p class="esporteCampeonato"><?php echo $pa->descricao ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
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