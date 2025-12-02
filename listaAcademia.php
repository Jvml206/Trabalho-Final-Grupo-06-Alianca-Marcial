<?php $nivelPermitido = ['Administrador'];
require_once 'validaUser.php';?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Academias</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>

    <main class="container mt-3">
        <div class="mt-3">
            <h1 class="tituloh1">Academias</h1>
        </div>
        <div class="mt-3">
            <a href="academia.php" class="btn btn-outline-success mb-3">Nova Academia</a>
        </div>
        <table class="table">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Nome da Academia</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <body>
                <?php
                spl_autoload_register(function ($class) {
                    require_once "Classes/{$class}.class.php";
                });

                $a = new Academia();
                $academias = $a->all();
                foreach ($academias as $academia):
                    ?>
                    <tr>
                        <td><?php echo $academia->id_academia ?></td>
                        <td><?php echo $academia->nome_fantasia ?></td>
                        <td class="d-flex gap-1 justify-content-center">
                            <form action="<?php echo htmlspecialchars("academia.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $academia->id_academia ?>">
                                <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                    onclick="return confirm('Tem certeza que deseja editar a academia?');">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </form>
                            <form action="<?php echo htmlspecialchars("academia.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $academia->id_academia ?>">
                                <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                    onclick="return confirm('Tem certeza que deseja deletar a academia?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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