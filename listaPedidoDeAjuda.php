<?php
$nivelPermitido = ['Administrador', 'Atleta'];
require_once 'validaUser.php';

$idUsuario = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['tipo_usuario'];

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$PedidoAjuda = new PedidoAjuda();
$Atleta = new Atleta();
$atletas = $Atleta->all();

if ($tipoUsuario === 'Atleta') {
    $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
    if (!$dadosAtleta) {
        die("Erro: Atleta não encontrado");
    }
}

if ($tipoUsuario === 'Atleta') {
    $pedidosAjuda = $PedidoAjuda->searchAll("fk_id_atleta", $dadosAtleta->id_atleta);
} else {
    // Administrador vê todos
    $pedidosAjuda = $PedidoAjuda->all();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Pedidos de Ajuda</title>
</head>

<body>
    <?php if ($tipoUsuario != 'Administrador'):
        require_once "_parts/_navSite.php";
    else:
        require_once "_parts/_navAdmin.php";
    endif;
    ?>

    <main class="container mt-3">
        <div class="mt-3">
            <h3>Pedidos de Ajuda</h3>
        </div>
        <div class="mt-3">
            <a href="pedidoDeAjuda.php" class="btn btn-outline-success mb-3">Novo Pedido de Ajuda</a>
        </div>
        <table class="table">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Atleta</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <body>
                <?php foreach ($pedidosAjuda as $pedidoAjuda): ?>
                    <tr>
                        <td><?php echo $pedidoAjuda->id_pedido_ajuda ?></td>
                        <td><?php echo $pedidoAjuda->titulo ?></td>
                        <td><?php foreach ($atletas as $atleta) {
                            if ($atleta->id_atleta == $pedidoAjuda->fk_id_atleta) {
                                echo $atleta->nome_atleta;
                                break;
                            }
                        } ?></td>
                        <td class="d-flex gap-1 justify-content-center">
                            <form action="<?php echo htmlspecialchars("pedidoDeAjuda.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $pedidoAjuda->id_pedido_ajuda ?>">
                                <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                    onclick="return confirm('Tem certeza que deseja editar o pedido de ajuda?');">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </form>
                            <form action="<?php echo htmlspecialchars("pedidoDeAjuda.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $pedidoAjuda->id_pedido_ajuda ?>">
                                <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                    onclick="return confirm('Tem certeza que deseja deletar o pedido de ajuda?');">
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
</body>

</html>