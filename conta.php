<?php
$nivelPermitido = ['Usuário'];
require_once 'validaUser.php';
$idUsuario = $_SESSION['user_id'];

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Usuario = new Usuario();

if (filter_has_var(INPUT_POST, "btnEditar")):

    $fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');
    $Usuario->setFoto($fotoAntiga);

    $Usuario->setNomeUsuario(filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING));
    $Usuario->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $Usuario->setTipoUsuario(filter_input(INPUT_POST, "tipo_usuario", FILTER_SANITIZE_STRING));
    $tipo_usuario = filter_input(INPUT_POST, "tipo_usuario", FILTER_SANITIZE_STRING);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $nomeFoto = uniqid("usuario_") . "." . $extensao;
            $destino = "Images/usuario/" . $nomeFoto;
            $caminhoAntigo = "Images/usuario/" . $fotoAntiga;

            if (!empty($fotoAntiga) && is_file($caminhoAntigo)) {
                unlink($caminhoAntigo);
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $Usuario->setFoto($nomeFoto);
            }
        }
    }

    if ($Usuario->update('id_usuario', $idUsuario)) {
        echo "<script>alert('Usuário alterado com sucesso.');window.location.href='conta.php';</script>";
    } else {
        echo "<script>alert('Erro ao alterar o usuário.');window.open(document.referrer,'_self');</script>";
    }
elseif (filter_has_var(INPUT_POST, "btnExcluirConta")):
    $delUsuario = $Usuario->search("id_usuario", $idUsuario);

    $fotoApagar = "Images/usuario/" . $delUsuario->foto;
    if (!empty($delUsuario->foto) && is_file($fotoApagar)) {
        unlink($fotoApagar);
    }

    if ($Usuario->delete("id_usuario", $idUsuario)) {
        require_once 'exclusaoConta.php';
    } else {
        echo "<script>alert('Erro ao excluir conta.'); window.open(document.referrer, '_self');</script>";
    }
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Conta</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php";

    $edtUsuario = new Usuario();
    $usuario = $edtUsuario->search("id_usuario", $idUsuario);
    ?>

    <main class="container">

        <h2 class="text-center">Conta</h2>

        <form action="conta.php" method="post" class="row g3 mt-3" enctype="multipart/form-data" id="form_valida_email">

            <input type="hidden" name="fotoAntiga" value="<?php echo $usuario->foto ?? ''; ?>">
            <div class="cadUsuario">
                <div class="dadosUsuario">
                    <div>
                        <label for="nome_usuario" class="form-label">Nome do Usuário</label>
                        <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Digite o Nome do Usuário"
                            required class="form-control" value="<?php echo $usuario->nome_usuario ?? null; ?>">
                    </div>

                    <div class="usuario">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" placeholder="Digite o Email do Usuário" required
                            class="form-control" value="<?php echo $usuario->email ?? null; ?>">
                    </div>

                    <div class="usuario">
                        <label for="confirmaEmail" class="form-label">Confirme o Email</label>
                        <input type="email" name="confirmaEmail" id="confirmaEmail"
                            placeholder="Digite a confirmação do E-mail" required class="form-control">
                        <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
                    </div>
                </div>

                <div class="fotoCadUsuario">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="form-control" <?php echo empty($usuario->foto) ? 'required' : null ?>>
                    <?php if (!empty($usuario->foto)): ?>
                        <img src="Images/usuario/<?php echo $usuario->foto; ?>" alt="Foto do Usuário"
                            class="mt-2 foto-usuario-cadastro">
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnEditar" id="btnEditar" class="btn btn-marrom">Salvar</button>
                <button type="submit" name="btnExcluirConta" id="btnExcluirConta" class="btn btn-danger">Excluir
                    Conta</button>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/controleEmail.js"></script>
</body>

</html>