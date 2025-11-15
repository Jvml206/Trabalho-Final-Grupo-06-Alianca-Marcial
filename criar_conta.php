<?php

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Usuario = new Usuario();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $Usuario->setNomeUsuario(filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING));
    $Usuario->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $Usuario->setSenha('senha');
    $Usuario->setTipoUsuario(filter_input(INPUT_POST, "tipo_usuario", FILTER_SANITIZE_STRING));

    $id_usuario = filter_input(INPUT_POST, "id_usuario", FILTER_SANITIZE_NUMBER_INT);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $nomeFoto = uniqid("usuario_") . "." . $extensao;
            $destino = "Images/usuario/" . $nomeFoto;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $Usuario->setFoto($nomeFoto);
            }
        }
    }

    if (empty($id_usuario)):
        if ($Usuario->add()) {
            // Monta mensagem personalizada
            $mensagem = "<p>{$Usuario->getNomeUsuario()},</p>
            <p>Seu cadastro foi realizado com sucesso! 🎉</p>
            <p>Antes de acessar sua conta, é necessário criar uma senha de acesso.</p>";

            // Envia o e-mail de recuperação/criação de senha
            $Usuario->solicitarRecuperacaoSenha(
                $Usuario->getEmail(),
                $mensagem,
                'Bem-vindo ao Sistema da Cooperativa Aliança Marcial'
            );

            echo "<script>alert('Cadastro de usuário realizado com sucesso! Um e-mail para definição de senha foi enviado para o endereço cadastrado.');window.location.href='usuario.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar o usuário.');window.open(document.referrer,'_self');</script>";
        }
    endif;
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
    <title>Cadastre-se</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php";?>

    <main class="container">

        <h2 class="text-center">Cadastre-se</h2>

        <form action="criar_conta.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">
            <div class="cadUsuario">
                <div class="dadosUsuario">
                    <div>
                        <label for="nome_usuario" class="form-label">Nome do Usuário</label>
                        <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Digite o Nome do Usuário"
                            required class="form-control">
                    </div>

                    <div class="usuario">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" placeholder="Digite o Email do Usuário" required
                            class="form-control">
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
                            <option disabled>Selecione o Tipo
                                de Usuário</option>
                            <option value="Atleta">Atleta
                            </option>
                            <option value="Instrutor">Instrutor
                            </option>
                            <option value="Usuário">Usuário
                            </option>
                        </select>
                    </div>
                </div>

                <div class="fotoCadUsuario">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="form-control" required>
                </div>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
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