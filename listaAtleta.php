<?php
$nivelPermitido = ['Administrador', 'Instrutor'];
require_once 'validaUser.php';

$idUsuario = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['tipo_usuario'];

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});
$Academia = new Academia();
$academias = $Academia->all();
$Instrutor = new Instrutor();
$instrutores = $Instrutor->all();
$Atleta = new Atleta();


if ($tipoUsuario === 'Instrutor') {
    $dadosInstrutor = $Instrutor->search("fk_id_usuario", $idUsuario);

    if (!$dadosInstrutor) {
        die("Erro: Instrutor não encontrado");
    }
}

if ($tipoUsuario === 'Instrutor') {
    $atletas = $Atleta->searchAll("fk_id_academia", $dadosInstrutor->fk_id_academia, "fk_id_instrutor", $dadosInstrutor->id_instrutor);
} else {
    // Administrador vê todos
    $atletas = $Atleta->all();
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
    <title>Atletas</title>
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
            <h3>Atleta</h3>
        </div>
        <div class="mt-3">
            <a href="atleta.php" class="btn btn-outline-success mb-3">Novo Atleta</a>
        </div>
        <table class="table">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Nome do Atleta</th>
                    <th>Esporte</th>
                    <th>Academia</th>
                    <th>Instrutor</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <body>
                <?php foreach ($atletas as $atleta): ?>
                    <tr>
                        <td><?php echo $atleta->id_atleta ?></td>
                        <td><?php echo $atleta->nome_atleta ?></td>
                        <td><?php echo $atleta->esporte ?></td>
                        <td><?php foreach ($academias as $a) {
                            if ($a->id_academia == $atleta->fk_id_academia) {
                                echo $a->nome_fantasia;
                                break;
                            }
                        } ?></td>
                        <td><?php foreach ($instrutores as $i) {
                            if ($i->id_instrutor == $atleta->fk_id_instrutor) {
                                echo $i->nome_instrutor;
                                break;
                            }
                        } ?></td>
                        <td class="d-flex gap-1 justify-content-center">
                            <form action="<?php echo htmlspecialchars("atleta.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $atleta->id_atleta ?>">
                                <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                    onclick="return confirm('Tem certeza que deseja editar o atleta?');">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </form>
                            <form action="<?php echo htmlspecialchars("atleta.php") ?>" method="post" class="d-flex">
                                <input type="hidden" name="id" value="<?php echo $atleta->id_atleta ?>">
                                <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                    onclick="return confirm('Tem certeza que deseja deletar o atleta?');">
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