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
    $Academia->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING));
    $Academia->setLink(filter_input(INPUT_POST, "link", FILTER_SANITIZE_STRING));
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
    <link rel="stylesheet" href="CSS/baseAdmin.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Academia</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>
    <main class="container cadastro">
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

        <h1 class="tituloh1">Cadastro de Academia</h1>

        <form action="academia.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <input type="hidden" value="<?php echo $Academia->id_academia ?? null; ?>" name="id_academia">

            <div class="cadAcademia">
                <div class="dadosAcademia col-md-6">
                    <label for="nome_fantasia" class="form-label tituloDado">Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" id="nome_fantasia"
                        placeholder="Digite o Nome Fantasia da Academia" required class="form-control"
                        value="<?php echo $Academia->nome_fantasia ?? null; ?>">

                    <div>
                        <label for="razao_social" class="form-label tituloDado">Razão Social</label>
                        <input type="text" name="razao_social" id="razao_social"
                            placeholder="Digite a Razão Social da Academia" required class="form-control"
                            value="<?php echo $Academia->razao_social ?? null; ?>">
                    </div>

                    <div>
                        <label for="cnpj" class="form-label tituloDado">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" placeholder="Digite o CNPJ da Academia"
                            required class="form-control" value="<?php echo $Academia->cnpj ?? null; ?>">
                    </div>

                    <div>
                        <label for="link" class="form-label tituloDado">Link</label>
                        <input type="url" name="link" id="link"
                            placeholder="Digite um link da Academia" required class="form-control"
                            value="<?php echo $Academia->link ?? null; ?>">
                    </div>
                </div>

                <div class="fotoCadAcademia">
                    <label for="logo" class="form-label tituloDado">Logo da Academia</label>
                    <input type="file" name="logo" id="logo" accept="image/*" class="form-control" <?php echo empty($Academia->logo) ? 'required' : null ?>>
                    <img src="<?= !empty($Academia->logo) ? 'Images/academia/' . $Academia->logo : 'Images\academia\SemFoto.png' ?>"
                        alt="Logo da Academia" class="mt-5 foto-academia-cadastro" id="fotoColocada">
                </div>
            </div>

            <div class="col-md-12">
                <label for="email" class="form-label tituloDado">Email</label>
                <input type="email" name="email" id="email" placeholder="Digite o Email da Academia" required
                    class="form-control" value="<?php echo $Academia->email ?? null; ?>">
            </div>
            <div class="col-md-12">
                <label for="confirmaEmail" class="form-label tituloDado">Confirme o Email</label>
                <input type="email" name="confirmaEmail" id="confirmaEmail" placeholder="Digite a confirmação do E-mail"
                    required class="form-control">
                <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
            </div>

            <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn-padrao">Salvar</button>
                <a href="listaAcademia.php" class="btn btn-voltar">Voltar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function enviarFormulario(url, codigo) {
                const formTemp = document.createElement('form');
                formTemp.method = 'POST';
                formTemp.action = url;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.value = codigo;
                formTemp.appendChild(input);
                document.body.appendChild(formTemp);
                formTemp.submit();
            }

            const email = document.querySelector('#email');
            const confirma = document.querySelector('#confirmaEmail');
            const mensagem = document.querySelector('#mensagem');
            const form = document.querySelector('#form_valida_email');

            if (!email || !confirma || !mensagem || !form) return;

            mensagem.style.display = "none";

            function emailValido(valor) {
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return regex.test(valor);
            }

            function validarEmails() {
                const valEmail = email.value.trim();
                const valConf = confirma.value.trim();

                if (valEmail.length === 0 && valConf.length === 0) {
                    mensagem.textContent = "";
                    mensagem.style.display = "none";
                    return false;
                }

                if (valEmail.length > 0 && !emailValido(valEmail)) {
                    mensagem.textContent = "❌ Formato de e-mail inválido";
                    mensagem.className = "alert alert-danger mt-2 mb-3";
                    mensagem.style.display = "block";
                    return false;
                }

                if (valConf.length > 0 && !emailValido(valConf)) {
                    mensagem.textContent = "❌ Formato de e-mail inválido na confirmação";
                    mensagem.className = "alert alert-danger mt-2 mb-3";
                    mensagem.style.display = "block";
                    return false;
                }
                
                if (valEmail.length > 0 && valConf.length > 0) {
                    if (valEmail !== valConf) {
                        mensagem.textContent = "❌ E-mails não conferem";
                        mensagem.className = "alert alert-danger mt-2 mb-3";
                        mensagem.style.display = "block";
                        return false;
                    } else {
                        mensagem.textContent = "✅ E-mails iguais";
                        mensagem.className = "alert alert-success mt-2 mb-3";
                        mensagem.style.display = "block";
                        return true;
                    }
                }

                mensagem.textContent = "";
                mensagem.style.display = "none";
                return false;
            }

            email.addEventListener('input', validarEmails);
            confirma.addEventListener('input', validarEmails);

            form.addEventListener('submit', function (e) {
                const ok = validarEmails();
                if (!ok) {
                    e.preventDefault();
                    alert("Corrija o email antes de enviar.");
                    if (!emailValido(email.value.trim())) {
                        email.focus();
                    } else {
                        confirma.focus();
                    }
                }
            });
        });
    </script>
    <script>
        $('#cnpj').mask('00.000.000/0000-00');
    </script>
    <!-- Foto -->
    <script>
        document.getElementById('logo').addEventListener('change', function (event) {
            const img = document.getElementById('fotoColocada');
            img.src = URL.createObjectURL(event.target.files[0]);
        })
    </script>
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