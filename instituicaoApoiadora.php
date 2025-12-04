<?php
$nivelPermitido = ['Administrador'];
require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$InstituicaoApoiadora = new InstituicaoApoiadora();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $InstituicaoApoiadora->setNomeFantasia(filter_input(INPUT_POST, "nome_fantasia", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setLink(filter_input(INPUT_POST, "link", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id_instituicao_apoiadora');

    $logoAntiga = filter_input(INPUT_POST, 'logoAntiga');
    $InstituicaoApoiadora->setLogo($logoAntiga);

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $nomeLogo = uniqid("instituicao_") . "." . $extensao;
            $destino = "Images/instituicao_apoiadora/" . $nomeLogo;
            $caminhoAntigo = "Images/instituicao_apoiadora/" . $logoAntiga;

            if (!empty($logoAntiga) && is_file($caminhoAntigo)) {
                unlink($caminhoAntigo);
            }

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destino)) {
                $InstituicaoApoiadora->setLogo($nomeLogo);
            }
        }
    }

    if (empty($id)):
        //Tenta adicionar e exibe a mensagem ao usuário
        if ($InstituicaoApoiadora->add()) {
            echo "<script>window.alert('Cadastro de instituição apoiadora realizado com sucesso.');window.location.href=instituicaoApoiadora.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a instituição apoiadora.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($InstituicaoApoiadora->update('id_instituicao_apoiadora', $id)) {
            echo "<script>window.alert('Instituição apoiadora alterada com sucesso.'); 
            window.location.href='listaInstituicoes.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar a Instituição apoiadora.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    $delInstituicaoApoiadora = $InstituicaoApoiadora->search("id_instituicao_apoiadora", $id);

    $fotoApagar = "Images/instituicao_apoiadora/" . $delInstituicaoApoiadora->logo;
    if (!empty($delInstituicaoApoiadora->logo) && is_file($fotoApagar)) {
        unlink($fotoApagar);
    }

    if ($InstituicaoApoiadora->delete("id_instituicao_apoiadora", $id)) {
        header("location:listaInstituicoes.php");
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
    <link rel="stylesheet" href="CSS/baseAdmin.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Instituição Apoiadora</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>
    <main class="container">
        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtInstituicaoApoiadora = new InstituicaoApoiadora();
            $id = intval(filter_input(INPUT_POST, "id"));
            $InstituicaoApoiadora = $edtInstituicaoApoiadora->search("id_instituicao_apoiadora", $id);
        }
        ?>

        <h1 class="tituloh1">Cadastro de Instituição Apoiadora</h1>

        <form action="instituicaoApoiadora.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <input type="hidden" value="<?php echo $InstituicaoApoiadora->id_instituicao_apoiadora ?? null; ?>"
                name="id_instituicao_apoiadora">
            <input type="hidden" name="logoAntiga" value="<?php echo $InstituicaoApoiadora->logo ?? ''; ?>">
            <div class="col-6">
                <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                <input type="text" name="nome_fantasia" id="nome_fantasia"
                    placeholder="Digite o Nome Fantasia da Instituição Apoiadora" required class="form-control"
                    value="<?php echo $InstituicaoApoiadora->nome_fantasia ?? null; ?>">
            </div>
            <div class="col-6">
                <label for="link" class="form-label">Link</label>
                <input type="url" name="link" id="link" placeholder="Digite o Link da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->link ?? null; ?>">
            </div>
            
            <label for="logo" class="form-label">Logo da Instituição</label>
            <input type="file" name="logo" id="logo" accept="image/*" class="form-control" <?php echo empty($InstituicaoApoiadora->logo) ? 'required' : null ?>>
            <?php if (!empty($InstituicaoApoiadora->logo)): ?>
                <img src="Images/instituicao_apoiadora/<?php echo $InstituicaoApoiadora->logo; ?>"
                    alt="Logo da Instituição Apoiadora" class="mt-2 foto-instituicao-cadastro">
            <?php endif; ?>

            <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn-padrao">Salvar</button>
                <a href="listaInstituicoes.php" class="btn btn-voltar">Voltar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="JS/controleEmail.js"></script>
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