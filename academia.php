<?php
$nivelPermitido = ['Administrador'];
require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Academia = new Academia();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Academia->setNomeFantasia(filter_input(INPUT_POST, "nome_fantasia", FILTER_SANITIZE_STRING));
    $Academia->setRazaoSocial(filter_input(INPUT_POST, "razao_social", FILTER_SANITIZE_STRING));
    $Academia->setCnpj(filter_input(INPUT_POST, "cnpj", FILTER_SANITIZE_STRING));
    $Academia->setTelefone(filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_STRING));
    $Academia->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING));
    $Academia->setEndereco(filter_input(INPUT_POST, "endereco", FILTER_SANITIZE_STRING));
    $Academia->setBairro(filter_input(INPUT_POST, "bairro", FILTER_SANITIZE_STRING));
    $Academia->setCidade(filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_STRING));
    $Academia->setCep(filter_input(INPUT_POST, "cep", FILTER_SANITIZE_STRING));
    $Academia->setEstado(filter_input(INPUT_POST, "estado", FILTER_SANITIZE_STRING));
    $Academia->setInstagram(filter_input(INPUT_POST, "instagram", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id_academia');

    $logoAntiga = filter_input(INPUT_POST, 'logoAntiga');
    $Academia->setLogo($logoAntiga);

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $nomeLogo = uniqid("academia_") . "." . $extensao;
            $destino = "Images/academia/" . $nomeLogo;
            $caminhoAntigo = "Images/academia/" . $logoAntiga;

            if (!empty($logoAntiga) && is_file($caminhoAntigo)) {
                unlink($caminhoAntigo);
            }

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destino)) {
                $Academia->setLogo($nomeLogo);
            }
        }
    }

    if (empty($id)):
        //Tenta adicionar e exibe a mensagem ao usuário
        if ($Academia->add()) {
            echo "<script>window.alert('Cadastro de academia realizado com sucesso.');window.location.href=academia.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a academia.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Academia->update('id_academia', $id)) {
            echo "<script>window.alert('Academia alterada com sucesso.'); 
            window.location.href='listaAcademia.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar a Academia.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    $delAcademia = $Academia->search("id_academia", $id);

    $fotoApagar = "Images/academia/" . $delAcademia->logo;
    if (!empty($delAcademia->logo) && is_file($fotoApagar)) {
        unlink($fotoApagar);
    }

    if ($Academia->delete("id_academia", $id)) {
        header("location:listaAcademia.php");
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
    <link rel="stylesheet" href="CSS/baseAdmin.css">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Academia</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>
    <main class="container">
        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtAcademia = new Academia();
            $id = intval(filter_input(INPUT_POST, "id"));
            $Academia = $edtAcademia->search("id_academia", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Academia</h2>

        <form action="academia.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <input type="hidden" value="<?php echo $Academia->id_academia ?? null; ?>" name="id_academia">

            <div class="row gap-4 mb-3">
                <div class="dadosAcademia col-md-6">
                    <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" id="nome_fantasia"
                        placeholder="Digite o Nome Fantasia da Academia" required class="form-control"
                        value="<?php echo $Academia->nome_fantasia ?? null; ?>">

                    <div class="academia">
                        <label for="razao_social" class="form-label">Razão Social</label>
                        <input type="text" name="razao_social" id="razao_social"
                            placeholder="Digite a Razão Social da Academia" required class="form-control"
                            value="<?php echo $Academia->razao_social ?? null; ?>">
                    </div>

                    <div class="academia">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" placeholder="Digite o CNPJ da Academia"
                            required class="form-control" value="<?php echo $Academia->cnpj ?? null; ?>">
                    </div>

                    <div class="academia">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" name="telefone" id="telefone"
                            placeholder="Digite o Telefone da Academia" required class="form-control"
                            value="<?php echo $Academia->telefone ?? null; ?>">
                    </div>

                    <div class="academia">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" name="instagram" id="instagram"
                            placeholder="Digite o Instagram da Academia" required class="form-control"
                            value="<?php echo $Academia->instagram ?? null; ?>">
                    </div>
                </div>

                <div class="logo">
                    <label for="logo" class="form-label">Logo da Academia</label>
                    <input type="file" name="logo" id="logo" accept="image/*" class="form-control" <?php echo empty($Academia->logo) ? 'required' : null ?>>
                    <?php if (!empty($Academia->logo)): ?>
                        <img src="Images/academia/<?php echo $Academia->logo; ?>"
                            alt="Logo da Academia" class="mt-2 foto-academia-cadastro">
                    <?php endif; ?>
                </div>
            </div>


            <div class="col-md-7">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" name="endereco" id="endereco"
                    placeholder="Digite o Endereço da Academia. Ex.: Rua São Paulo n°1532" required class="form-control"
                    value="<?php echo $Academia->endereco ?? null; ?>">
            </div>

            <div class="col-md-5">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" name="bairro" id="bairro" placeholder="Digite o Bairro da Academia" required
                    class="form-control" value="<?php echo $Academia->bairro ?? null; ?>">
            </div>

            <div class="col-md-7">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" name="cidade" id="cidade" placeholder="Digite a Cidade da Academia" required
                    class="form-control" value="<?php echo $Academia->cidade ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" name="cep" id="cep" placeholder="Digite o CEP da Academia" required
                    class="form-control" value="<?php echo $Academia->cep ?? null; ?>">
            </div>

            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($Academia->estado)) ? 'selected' : '' ?>>UF</option>
                    <option value="AC" <?= (isset($Academia->estado) && $Academia->estado == 'AC') ? 'selected' : '' ?>>AC
                    </option>
                    <option value="AL" <?= (isset($Academia->estado) && $Academia->estado == 'AL') ? 'selected' : '' ?>>AL
                    </option>
                    <option value="AP" <?= (isset($Academia->estado) && $Academia->estado == 'AP') ? 'selected' : '' ?>>AP
                    </option>
                    <option value="AM" <?= (isset($Academia->estado) && $Academia->estado == 'AM') ? 'selected' : '' ?>>AM
                    </option>
                    <option value="BA" <?= (isset($Academia->estado) && $Academia->estado == 'BA') ? 'selected' : '' ?>>BA
                    </option>
                    <option value="CE" <?= (isset($Academia->estado) && $Academia->estado == 'CE') ? 'selected' : '' ?>>CE
                    </option>
                    <option value="DF" <?= (isset($Academia->estado) && $Academia->estado == 'DF') ? 'selected' : '' ?>>DF
                    </option>
                    <option value="ES" <?= (isset($Academia->estado) && $Academia->estado == 'ES') ? 'selected' : '' ?>>ES
                    </option>
                    <option value="GO" <?= (isset($Academia->estado) && $Academia->estado == 'GO') ? 'selected' : '' ?>>GO
                    </option>
                    <option value="MA" <?= (isset($Academia->estado) && $Academia->estado == 'MA') ? 'selected' : '' ?>>MA
                    </option>
                    <option value="MS" <?= (isset($Academia->estado) && $Academia->estado == 'MS') ? 'selected' : '' ?>>MS
                    </option>
                    <option value="MT" <?= (isset($Academia->estado) && $Academia->estado == 'MT') ? 'selected' : '' ?>>MT
                    </option>
                    <option value="MG" <?= (isset($Academia->estado) && $Academia->estado == 'MG') ? 'selected' : '' ?>>MG
                    </option>
                    <option value="PA" <?= (isset($Academia->estado) && $Academia->estado == 'PA') ? 'selected' : '' ?>>PA
                    </option>
                    <option value="PB" <?= (isset($Academia->estado) && $Academia->estado == 'PB') ? 'selected' : '' ?>>PB
                    </option>
                    <option value="PR" <?= (isset($Academia->estado) && $Academia->estado == 'PR') ? 'selected' : '' ?>>PR
                    </option>
                    <option value="PE" <?= (isset($Academia->estado) && $Academia->estado == 'PE') ? 'selected' : '' ?>>PE
                    </option>
                    <option value="PI" <?= (isset($Academia->estado) && $Academia->estado == 'PI') ? 'selected' : '' ?>>PI
                    </option>
                    <option value="RJ" <?= (isset($Academia->estado) && $Academia->estado == 'RJ') ? 'selected' : '' ?>>RJ
                    </option>
                    <option value="RN" <?= (isset($Academia->estado) && $Academia->estado == 'RN') ? 'selected' : '' ?>>RN
                    </option>
                    <option value="RS" <?= (isset($Academia->estado) && $Academia->estado == 'RS') ? 'selected' : '' ?>>RS
                    </option>
                    <option value="RO" <?= (isset($Academia->estado) && $Academia->estado == 'RO') ? 'selected' : '' ?>>RO
                    </option>
                    <option value="RR" <?= (isset($Academia->estado) && $Academia->estado == 'RR') ? 'selected' : '' ?>>RR
                    </option>
                    <option value="SC" <?= (isset($Academia->estado) && $Academia->estado == 'SC') ? 'selected' : '' ?>>SC
                    </option>
                    <option value="SP" <?= (isset($Academia->estado) && $Academia->estado == 'SP') ? 'selected' : '' ?>>SP
                    </option>
                    <option value="SE" <?= (isset($Academia->estado) && $Academia->estado == 'SE') ? 'selected' : '' ?>>SE
                    </option>
                    <option value="TO" <?= (isset($Academia->estado) && $Academia->estado == 'TO') ? 'selected' : '' ?>>TO
                    </option>
                </select>
            </div>

            <div class="col-md-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="Digite o Email da Academia" required
                    class="form-control" value="<?php echo $Academia->email ?? null; ?>">
            </div>
            <div class="col-md-12">
                <label for="confirmaEmail" class="form-label">Confirme o Email</label>
                <input type="email" name="confirmaEmail" id="confirmaEmail" placeholder="Digite a confirmação do E-mail"
                    required class="form-control">
                <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-success">Salvar</button>
                <a href="listaAcademia.php" class="btn btn-outline-danger">Voltar</a>
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