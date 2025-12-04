<?php $nivelPermitido = ['Administrador'];
require_once 'validaUser.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Campeonatos</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>

    <main class="container">
        <div class="mt-3">
            <h1 class="tituloh1">Campeonatos</h1>
        </div>
        <div class="mt-3">
            <a href="campeonato.php" class="btn btn-novo mb-3">Novo Campeonato</a>
        </div>
        <table class="table tabela text-center overflow-hidden table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome do Campeonato</th>
                    <th>Esporte</th>
                    <th>Data de Início</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <body>
                <?php
                spl_autoload_register(function ($class) {
                    require_once "Classes/{$class}.class.php";
                });

                $c = new Campeonato();
                $campeonatos = $c->all();
                foreach ($campeonatos as $campeonato):
                    ?>
                    <tr>
                        <td><?php echo $campeonato->id_campeonato ?></td>
                        <td><?php echo $campeonato->nome_campeonato ?></td>
                        <td><?php echo $campeonato->esporte ?></td>
                        <td><?php echo date('d/m/Y', strtotime($campeonato->data_inicio)) ?></td>
                        <td class="align-middle">
                            <div class="d-flex gap-1 justify-content-center">
                            <form action="<?php echo htmlspecialchars("campeonato.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $campeonato->id_campeonato ?>">
                                <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                    onclick="return confirm('Tem certeza que deseja editar o campeonato?');">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </form>
                            <form action="<?php echo htmlspecialchars("campeonato.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $campeonato->id_campeonato ?>">
                                <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                    onclick="return confirm('Tem certeza que deseja deletar o campeonato?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </body>
        </table>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
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