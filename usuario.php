<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Usuario = new Usuario();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');
    $Usuario->setFoto($fotoAntiga);

    $Usuario->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $senhaC = password_hash('senhaprovisoria', PASSWORD_DEFAULT);
    $usuario->setSenhaUsuario($senhaC);
    $Usuario->setTipoUsuario(filter_input(INPUT_POST, "tipo_usuario", FILTER_SANITIZE_STRING));

    $id_usuario = filter_input(INPUT_POST, "id_usuario", FILTER_SANITIZE_NUMBER_INT);

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

    if (empty($id_usuario)):
        if ($Usuario->add()) {
            echo "<script>alert('Cadastro de usuário realizado com sucesso, um email para definição de senha foi enviado para o email cadastrado.');window.location.href='usuario.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar o usuário.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Usuario->update('id_usuario', $id_usuario)) {
            echo "<script>alert('Usuário alterado com sucesso.');window.location.href='listaUsuario.php';</script>";
        } else {
            echo "<script>alert('Erro ao alterar o usuário.');window.open(document.referrer,'_self');</script>";
        }
    endif;

elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id_usuario = intval(filter_input(INPUT_POST, "id_usuario"));
    $delUsuario = $Usuario->search("id_usuario", $id_usuario);

    $fotoApagar = "Images/usuario/" . $delUsuario->foto;
    if (!empty($delUsuario->foto) && is_file($fotoApagar)) {
        unlink($fotoApagar);
    }

    if ($Usuario->delete("id_usuario", $id_usuario)) {
        header("location:listaUsuario.php");
    } else {
        echo "<script>alert('Erro ao excluir o usuário.'); window.open(document.referrer, '_self');</script>";
    }
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseAdmin.css">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Usuário</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>
    <main class="container">
        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtUsuario = new Usuario();
            $id = intval(filter_input(INPUT_POST, "id"));
            $Usuario = $edtUsuario->search("id_usuario", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Usuário</h2>

        <form action="usuario.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <input type="hidden" value="<?php echo $Usuario->id_usuario ?? null; ?>" name="id_usuario">
            <div class="cadUsuario">
                <div class="dadosUsuario">
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" placeholder="Digite o Email do Usuário" required
                            class="form-control" value="<?php echo $Usuario->email ?? null; ?>">
                    </div>
                    <div class="usuario">
                        <label for="confirmaEmail" class="form-label">Confirme o Email</label>
                        <input type="email" name="confirmaEmail" id="confirmaEmail"
                            placeholder="Digite a confirmação do E-mail" required class="form-control">
                        <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
                    </div>

                    <div class="usuario">
                        <label for="tipo_usuario" class="form-label">Tipo de Usuário</label>
                        <select id="tipo_usuario" name="tipo_usuario" class="form-select"
                            aria-label="Default select example">
                            <option disabled <?= (!isset($Usuario->tipo_usuario)) ? 'selected' : '' ?>>Selecione o Tipo
                                de
                                Usuário</option>
                            <option value="Administrador" <?= (isset($Usuario->tipo_usuario) && $Usuario->tipo_usuario == 'Administrador') ? 'selected' : '' ?>>Administrador
                            </option>
                            <option value="Academia" <?= (isset($Usuario->tipo_usuario) && $Usuario->tipo_usuario == 'Academia') ? 'selected' : '' ?>>Academia
                            </option>
                            <option value="Atleta" <?= (isset($Usuario->tipo_usuario) && $Usuario->tipo_usuario == 'Atleta') ? 'selected' : '' ?>>Atleta
                            </option>
                            <option value="Instrutor" <?= (isset($Usuario->tipo_usuario) && $Usuario->tipo_usuario == 'Instrutor') ? 'selected' : '' ?>>Instrutor
                            </option>
                            <option value="Usuário" <?= (isset($Usuario->tipo_usuario) && $Usuario->tipo_usuario == 'Usuário') ? 'selected' : '' ?>>Usuário
                            </option>
                        </select>
                    </div>
                </div>

                <div class="fotoCadUsuario">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="form-control" required>
                    <?php if (!empty($Usuario->foto)): ?>
                        <img src="Images/usuario/<?php echo $Usuario->foto; ?>" alt="Foto do Usuário"
                            class="mt-2 foto-usuario-cadastro">
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                <a href="listaUsuario.php" class="btn btn-outline-danger">Voltar</a>
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