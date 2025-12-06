<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/index.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Academias</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <?php
    spl_autoload_register(function ($class) {
        require_once "Classes/{$class}.class.php";
    });
    $Academia = new Academia();
    $Instrutor = new Instrutor();
    ?>
    <main class="container">
        <h1 class="tituloh1">Academias</h1>
        <div class="box">
            <?php
            $academia = $Academia->all();
            foreach ($academia as $a):
                $id = intval($a->id_academia);
                ?>
                <div class="cardAcademia d-flex align-items-center flex-column">
                    <div class="card-img-container-academia">
                        <img src="Images/academia/<?php echo $a->logo; ?>" title="<?php echo $a->nome_fantasia; ?>"
                            alt="<?php echo $a->nome_fantasia; ?>" class="foto-instituicao">
                    </div>

                    <p class="nomeAcademia"><?php echo $a->nome_fantasia; ?></p>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-ajudar" data-bs-toggle="modal"
                            data-bs-target="#pedidoModal<?php echo $id; ?>" onclick="this.blur()">
                            Ver Mais
                        </button>
                    </div>
                </div>

                <div class="modal fade" id="pedidoModal<?php echo $id; ?>" tabindex="-1"
                    aria-labelledby="pedidoModalLabel<?php echo $id; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="pedidoModalLabel<?php echo $id; ?>">
                                    <?php echo $a->nome_fantasia ?>
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="nomeAcademia">Razão social: <?php echo $a->razao_social ?></p>
                                <p class="cnpjAcademia">CNPJ: <?php echo $a->cnpj ?></p>
                                <p class="linkAcadAtleta">Link: <a href="<?php echo $a->link ?>" target="_blank"><?php echo $a->link ?></a>
                                </p>
                                <h4 class="nomeAcademia">Instrutores:</h4>
                                <?php $instrutor = $Instrutor->instrutoresValidosAcademia('fk_id_academia', $id); ?>
                                <?php if ($instrutor == null) { ?>
                                    <p>Sem instrutor cadastrado</p>
                                <?php }else{ ?>
                                <ul>
                                    <li><?= $instrutor->nome_instrutor ?? null ?></li>
                                </ul>
                                <?php } ?>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-modal" data-bs-dismiss="modal">Fechar</button>
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