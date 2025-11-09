<?php
require_once 'validaUser.php';
$tipoUsuario = $_SESSION['tipo_usuario'] ?? '';

// Bloqueia o acesso se for "Usuário"
if ($tipoUsuario === 'Usuário') {
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href='index.php';</script>";
    exit;
}

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});
$Academia = new Academia();
$academias = $Academia->all();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Instrutores</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>

    <main class="container mt-3">
        <div class="mt-3">
            <h3>Instrutores</h3>
        </div>
        <div class="mt-3">
            <a href="instrutor.php" class="btn btn-outline-success mb-3">Novo Instrutor</a>
        </div>
        <table class="table">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Nome do Instrutor</th>
                    <th>Academia de trabalho</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <body>
                <?php
                spl_autoload_register(function ($class) {
                    require_once "Classes/{$class}.class.php";
                });

                $i = new Instrutor();
                $instrutores = $i->all();
                foreach ($instrutores as $instrutor):
                    ?>
                    <tr>
                        <td><?php echo $instrutor->id_instrutor ?></td>
                        <td><?php echo $instrutor->nome_instrutor ?></td>
                        <td><?php foreach ($academias as $a) {
                            if ($a->id_academia == $instrutor->fk_id_academia) {
                                echo $a->nome_fantasia;
                                break;
                            }
                        } ?></td>
                        <td class="d-flex gap-1 justify-content-center">
                            <form action="<?php echo htmlspecialchars("instrutor.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $instrutor->id_instrutor ?>">
                                <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                    onclick="return confirm('Tem certeza que deseja editar o instrutor?');">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </form>
                            <form action="<?php echo htmlspecialchars("instrutor.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $instrutor->id_instrutor ?>">
                                <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                    onclick="return confirm('Tem certeza que deseja deletar o instrutor?');">
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