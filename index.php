<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/index.css?v=<?php echo time(); ?>">
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
    $Campeonato = new Campeonato();
    $PedidoAjuda = new PedidoAjuda();
    $InstituicaoApoiadora = new InstituicaoApoiadora();
    ?>

    <div class="topo">
        <div class="overlay-container-topo">
            <img src="Images/artes_marciais.png" alt="Foto do topo da página" class="overlay-image-topo">
            <div class="overlay-text-topo">
                <h1>Aliança Marcial</h1>
                <h2>O local onde você coopera e é cooperado</h2>
            </div>
        </div>
    </div>

    <main class="container">
        <section class="ajuda mt-2">
            <h2 class="text-center texto">Atletas que precisam de ajuda</h2>
            <div class="box">
                <?php
                $pedidoAjuda = $PedidoAjuda->allIndex();
                foreach (array_slice($pedidoAjuda, 0, 12) as $pa):
                    $id = intval($pa->id_pedido_ajuda);
                    $idA = intval($pa->fk_id_atleta);
                    $atleta = $Atleta->search('id_atleta', $idA);
                    $academia = $Academia->search('id_academia', $atleta->fk_id_academia);
                    ?>
                    <div class="cardPedido d-flex align-items-center flex-column">
                        <div class="card-img-container-academia">
                            <img src="Images/pedidoDeAjuda/<?php echo $pa->imagem; ?>">
                        </div>
                        <p class="nomeAtleta text-center"><?php echo $atleta->nome_atleta; ?></p>
                        <p class="motivoAtleta text-center"><?php echo $pa->titulo; ?></p>
                        <p class="academiaAtleta text-center">Academia: <?php echo $academia->nome_fantasia; ?></p>
                        <button type="button" class="btn btn-ajudar" data-bs-toggle="modal"
                            data-bs-target="#pedidoModal<?php echo $id; ?>" onclick="this.blur()">
                            Ajudar
                        </button>
                    </div>

                    <div class="modal fade" id="pedidoModal<?php echo $id; ?>" tabindex="-1"
                        aria-labelledby="pedidoModalLabel<?php echo $id; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="pedidoModalLabel<?php echo $id; ?>">
                                        <?php echo $pa->titulo ?>
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                            <?php echo number_format(($pa->valor_atingido / $pa->valor_necessario) * 100, 0, ',', '.'); ?>%
                                        </div>
                                    </div>
                                    <p class="descricaoPedido justify"><?php echo $pa->descricao ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-modal" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagAjuda align-items-center text-center mt-3">
                <a href="pedidosDeAjuda.php" class="btn btn-verMais">Ver pedidos de ajuda</a>
            </div>
        </section>

        <section class="campeonatos mt-2">
            <h2 class="text-center texto">Próximos Campeonatos</h2>
            <div class="box">
                <?php
                $campeonato = $Campeonato->allIndex();
                foreach ($campeonato as $c):
                    $id = intval($c->id_campeonato);
                    ?>
                    <div class="cardCampeonato d-flex align-items-center flex-column">
                        <div class="overlay-container">
                            <img src="Images/campeonato/<?php echo $c->esporte; ?>.jpg" class="img-camp">
                            <div class="overlay-text">
                                <p class="nomeCampeonato"><?php echo $c->nome_campeonato ?></p>
                                <p class="paisCampeonato"><?php echo $c->pais ?></p>
                                <p class="esporteCampeonato"><?php echo $c->esporte ?></p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-campeonato" data-bs-toggle="modal"
                            data-bs-target="#campeonatoModal<?php echo $id; ?>" onclick="this.blur()">
                            Ver mais
                        </button>
                    </div>

                    <div class="modal fade" id="campeonatoModal<?php echo $id; ?>" tabindex="-1"
                        aria-labelledby="campeonatoModalLabel<?php echo $id; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="campeonatoModalLabel<?php echo $id; ?>">
                                        <?php echo $c->nome_campeonato ?>
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="localCampeonato">Local: <?php echo $c->local ?></p>
                                    <p class="paisCampeonato">País: <?php echo $c->pais ?></p>
                                    <p class="cidadeCampeonato">Cidade: <?php echo $c->cidade ?></p>
                                    <p class="esporteCampeonato">Esporte: <?php echo $c->esporte ?></p>
                                    <p class="dataCampeonato">De <?php echo date('d/m/Y', strtotime($c->data_inicio)) ?>
                                        à <?php echo date('d/m/Y', strtotime($c->data_fim)) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-modal" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagCampeonato">
                <a href="campeonatos.php" class="btn btn-verMais">Ver campeonatos</a>
            </div>
        </section>

        <h1 class="tituloh1">Instituições que nos apoiam</h1>
        <div class="box">
            <?php
            $instituicaoApoiadora = $InstituicaoApoiadora->all();
            foreach ($instituicaoApoiadora as $ia):
                $id = intval($ia->id_instituicao_apoiadora);
                ?>
                <div class="cardinstituicao d-flex align-items-center flex-column">
                    <a href="<?php echo $ia->link; ?>" target="_blank"> <img
                            src="Images/instituicao_apoiadora/<?php echo $ia->logo; ?>"
                            title="<?php echo $ia->nome_fantasia; ?>" alt="<?php echo $ia->nome_fantasia; ?>"
                            class="foto-instituicao"></a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>