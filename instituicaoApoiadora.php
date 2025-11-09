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

$InstituicaoApoiadora = new InstituicaoApoiadora();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $InstituicaoApoiadora->setNomeFantasia(filter_input(INPUT_POST, "nome_fantasia", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setRazaoSocial(filter_input(INPUT_POST, "razao_social", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setCnpj(filter_input(INPUT_POST, "cnpj"));
    $InstituicaoApoiadora->setTelefone(filter_input(INPUT_POST, "telefone"));
    $InstituicaoApoiadora->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setEndereco(filter_input(INPUT_POST, "endereco", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setBairro(filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setCidade(filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setCep(filter_input(INPUT_POST, "cep"));
    $InstituicaoApoiadora->setEstado(filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setInstagram(filter_input(INPUT_POST, "instagram", FILTER_SANITIZE_STRING));
    $InstituicaoApoiadora->setDescricao(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id_instituicao_apoiadora');
    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($InstituicaoApoiadora->add()) {
            echo "<script>window.alert('Cadastro de instituição apoiadora realizado com sucesso.');window.location.href=instituicaoApoiadora.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a instituição apoiadora.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($InstituicaoApoiadora->update('id_instituicao_apoiadora', $id)) {
            echo "<script>window.alert('Instituição apoiadora alterada com sucesso.'); 
            window.location.href='listaInstituicaoApoiadora.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar a Instituição apoiadora.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    if ($InstituicaoApoiadora->delete("id_instituicao_apoiadora", $id)) {
        header("location:listaInstituicaoApoiadora.php");
    } else {
        echo "<script>window.alert('Erro ao excluir'); window.open(document.referrer, '_self');</script>";
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

        <h2 class="text-center">Cadastro de Instituição Apoiadora</h2>

        <form action="instituicaoApoiadora.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <input type="hidden" value="<?php echo $InstituicaoApoiadora->id_instituicao_apoiadora ?? null; ?>" name="id_instituicao_apoiadora">

            <div class="nome col-md-6">
                <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                <input type="text" name="nome_fantasia" id="nome_fantasia"
                    placeholder="Digite o Nome Fantasia da Instituição Apoiadora" required class="form-control"
                    value="<?php echo $InstituicaoApoiadora->nome_fantasia ?? null; ?>">

                <label for="razao_social" class="form-label">Razão Social</label>
                <input type="text" name="razao_social" id="razao_social" placeholder="Digite a Razão Social da Instituição Apoiadora"
                    required class="form-control" value="<?php echo $InstituicaoApoiadora->razao_social ?? null; ?>">
            </div>

            <div class="col-md-4 logo">
                <label for="logo" class="form-label">Logo da Instituição Apoiadora</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="form-control">
                <?php if (!empty($InstituicaoApoiadora->foto)): ?>
                    <img src="Images/instituicao_apoiadora/<?php echo $InstituicaoApoiadora->foto; ?>" alt="Foto da Instituição Apoiadora"
                        class="mt-2 foto-academia-cadastro">
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" name="cnpj" id="cnpj" placeholder="Digite o CNPJ da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->cnpj ?? null; ?>">
            </div>

            <div class="col-md-4">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone" placeholder="Digite o Telefone da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->telefone ?? null; ?>">
            </div>

            <div class="col-md-4">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="text" name="instagram" id="instagram" placeholder="Digite o Instagram da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->instagram ?? null; ?>">
            </div>

            <div class="col-md-7">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" name="endereco" id="endereco"
                    placeholder="Digite o Endereço da Instituição Apoiadora. Ex.: Rua São Paulo n°1532" required class="form-control"
                    value="<?php echo $InstituicaoApoiadora->endereco ?? null; ?>">
            </div>

            <div class="col-md-5">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" name="bairro" id="bairro" placeholder="Digite o Bairro da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->bairro ?? null; ?>">
            </div>

            <div class="col-md-7">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" name="cidade" id="cidade" placeholder="Digite a Cidade da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->cidade ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" name="cep" id="cep" placeholder="Digite o CEP da Instituição Apoiadora" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->cep ?? null; ?>">
            </div>

            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($InstituicaoApoiadora->estado)) ? 'selected' : '' ?>>UF</option>
                    <option value="AC" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'AC') ? 'selected' : '' ?>>AC
                    </option>
                    <option value="AL" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'AL') ? 'selected' : '' ?>>AL
                    </option>
                    <option value="AP" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'AP') ? 'selected' : '' ?>>AP
                    </option>
                    <option value="AM" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'AM') ? 'selected' : '' ?>>AM
                    </option>
                    <option value="BA" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'BA') ? 'selected' : '' ?>>BA
                    </option>
                    <option value="CE" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'CE') ? 'selected' : '' ?>>CE
                    </option>
                    <option value="DF" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'DF') ? 'selected' : '' ?>>DF
                    </option>
                    <option value="ES" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'ES') ? 'selected' : '' ?>>ES
                    </option>
                    <option value="GO" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'GO') ? 'selected' : '' ?>>GO
                    </option>
                    <option value="MA" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'MA') ? 'selected' : '' ?>>MA
                    </option>
                    <option value="MS" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'MS') ? 'selected' : '' ?>>MS
                    </option>
                    <option value="MT" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'MT') ? 'selected' : '' ?>>MT
                    </option>
                    <option value="MG" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'MG') ? 'selected' : '' ?>>MG
                    </option>
                    <option value="PA" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'PA') ? 'selected' : '' ?>>PA
                    </option>
                    <option value="PB" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'PB') ? 'selected' : '' ?>>PB
                    </option>
                    <option value="PR" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'PR') ? 'selected' : '' ?>>PR
                    </option>
                    <option value="PE" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'PE') ? 'selected' : '' ?>>PE
                    </option>
                    <option value="PI" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'PI') ? 'selected' : '' ?>>PI
                    </option>
                    <option value="RJ" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'RJ') ? 'selected' : '' ?>>RJ
                    </option>
                    <option value="RN" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'RN') ? 'selected' : '' ?>>RN
                    </option>
                    <option value="RS" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'RS') ? 'selected' : '' ?>>RS
                    </option>
                    <option value="RO" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'RO') ? 'selected' : '' ?>>RO
                    </option>
                    <option value="RR" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'RR') ? 'selected' : '' ?>>RR
                    </option>
                    <option value="SC" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'SC') ? 'selected' : '' ?>>SC
                    </option>
                    <option value="SP" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'SP') ? 'selected' : '' ?>>SP
                    </option>
                    <option value="SE" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'SE') ? 'selected' : '' ?>>SE
                    </option>
                    <option value="TO" <?= (isset($InstituicaoApoiadora->estado) && $InstituicaoApoiadora->estado == 'TO') ? 'selected' : '' ?>>TO
                    </option>
                </select>
            </div>

            <div class="col-md-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="Digite o Email da Instituição" required
                    class="form-control" value="<?php echo $InstituicaoApoiadora->email ?? null; ?>">
            </div>
            <div class="col-md-12">
                <label for="confirmaEmail" class="form-label">Confirme o Email</label>
                <input type="email" name="confirmaEmail" id="confirmaEmail" placeholder="Digite a confirmação do E-mail"
                    required class="form-control">
                <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
            </div>

            <div class="col-12">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea type="text" name="descricao" id="descricao" placeholder="Digite uma descrição, no mínimo 200 caracteres"
                    required minlength="200" class="form-control"><?php echo $InstituicaoApoiadora->descricao ?? null; ?></textarea>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-success">Salvar</button>
                <a href="listaInstituicoes.php" class="btn btn-outline-danger">Voltar</a>
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
    <script>
        $('#telefone').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');
        $('#cnpj').mask('00.000.000/0000-00');
    </script>
</body>

</html>