<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="icon" href="Images/logo.png">
    <title>Campeonatos</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <?php
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });
    $Campeonato = new Campeonato();
    ?>
    <main>
        <h1 class="tituloh1">Próximos Campeonatos</h1>

        <div class="box">
            <?php
            $campeonato = $Campeonato->allCamp();
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
                        data-bs-target="#campeonatoModal<?php echo $id; ?>">
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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