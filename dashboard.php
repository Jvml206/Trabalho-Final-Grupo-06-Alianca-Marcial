<?php require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$dado = new Usuario();

$dados = $dado->sp_exibir('dashboard_totais();');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/dashboard.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Aliança Marcial</title>
</head>

<body>

    <?php require_once "_parts/_navAdmin.php"; ?>

    <main class="container py-4">
        <h2 class="text-center dashboard-title tituloh1">📊 Dashboard da Cooperativa Aliança Marcial</h2>
        <div class="row g-4 d-flex justify-content-center cards">

            <!-- Usuários -->
            <div class="col-md-3">
                <a href="listaUsuario.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">🙍‍♂️</span>
                        <h5>Usuários</h5>
                        <h2><?= $dados[0]->totalUsuarios ?></h2>
                    </div>
                </a>
            </div>

            <!-- Atletas -->
            <div class="col-md-3">
                <a href="listaAtleta.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">🏋️‍♀️</span>
                        <h5>Atletas</h5>
                        <h2><?= $dados[0]->totalAtletas ?></h2>
                    </div>
                </a>
            </div>

            <!-- Instrutores -->
            <div class="col-md-3">
                <a href="listaInstrutor.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">👨‍🏫</span>
                        <h5>Instrutores</h5>
                        <h2><?= $dados[0]->totalInstrutores ?></h2>
                    </div>
                </a>
            </div>

            <!-- Academias -->
            <div class="col-md-3">
                <a href="listaAcademia.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">🏫</span>
                        <h5>Academias</h5>
                        <h2><?= $dados[0]->totalAcademias ?></h2>
                    </div>
                </a>
            </div>

            <!-- Instituições -->
            <div class="col-md-3">
                <a href="listaInstituicoes.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">🏛️</span>
                        <h5>Instituições Apoiadoras</h5>
                        <h2><?= $dados[0]->totalInstituicao ?></h2>
                    </div>
                </a>
            </div>

            <!-- Campeonatos -->
            <div class="col-md-3">
                <a href="listaCampeonato.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">🏆</span>
                        <h5>Campeonatos</h5>
                        <h2><?= $dados[0]->totalCampeonatos ?></h2>
                    </div>
                </a>
            </div>

            <!-- Pedidos de Ajuda -->
            <div class="col-md-3">
                <a href="listaPedidoDeAjuda.php">
                    <div class="card-modern text-center p-3">
                        <span class="icon">🤝</span>
                        <h5>Pedidos de Ajuda</h5>
                        <h2><?= $dados[0]->totalPedidosAjuda ?></h2>
                    </div>
                </a>
            </div>
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