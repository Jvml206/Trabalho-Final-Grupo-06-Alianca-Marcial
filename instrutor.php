<?php
if (filter_has_var(INPUT_POST, "btnEditar")) {
    $nivelPermitido = ['Administrador', 'Instrutor'];
}
else{
    $nivelPermitido = ['Instrutor'];
}
require_once 'validaUser.php';

$idUsuario = $_SESSION['user_id'];
$nome = $_SESSION['user_name'];
$email = $_SESSION['user_email'];
$tipoUsuario = $_SESSION['tipo_usuario'];

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Instrutor = new Instrutor();
$Usuario = new Usuario();
$usuario = $Usuario->all();
$Academia = new Academia();
$academia = $Academia->all();

$usuariosDisponiveis = $Usuario->usuariosInstrutorDisponiveis();

if ($tipoUsuario === 'Instrutor') {
    $existe = $Instrutor->verificarPorUsuario($idUsuario);
    if ($existe) {
        // Se já existir cadastro, busca os dados para edição
        $instrutor = $Instrutor->search("fk_id_usuario", $idUsuario);
    }
}

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Instrutor->setTelefone(filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_STRING));
    $Instrutor->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
    $id = filter_input(INPUT_POST, 'id_instrutor');


    if ($tipoUsuario === 'Administrador') {
        $nomeInstrutor = trim(filter_input(INPUT_POST, "nome_instrutor", FILTER_SANITIZE_STRING));
        $usuarioEncontrado = $Usuario->searchInstrutor("nome_usuario", $nomeInstrutor);
        $fkUsuario = $Usuario->search("id_usuario", $usuarioEncontrado->id_usuario);

        $Instrutor->setNomeInstrutor($nomeInstrutor);
        $Instrutor->setEmail($fkUsuario->email);
        $Instrutor->setFkIdUsuario($fkUsuario->id_usuario);
    } else {
        $Instrutor->setNomeInstrutor($nome);
        $Instrutor->setEmail($email);
        $Instrutor->setFkIdUsuario($idUsuario);
    }

    if (empty($id)):
        if ($Instrutor->add()) {
            $idInstrutor = $Instrutor->lastId();
            $mensagem = "Por favor, valide o cadastro clicando no link abaixo:";
            $assunto = "Validação de novo instrutor";
            $Instrutor->enviarValidacaoAcademia($idInstrutor, $mensagem, $assunto);

            echo "<script>
            window.alert('Cadastro realizado! Aguarde a academia validar a conta.');
            window.location.href='instrutor.php';
          </script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o instrutor.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Instrutor->update('id_instrutor', $id)) {

            if ($tipoUsuario === 'Instrutor') {

                $usuarioAtual = $Usuario->search("id_usuario", $idUsuario);
                if ($tipoUsuario === 'Administrador') {
                    $instrutorAtual = $Instrutor->search("id_instrutor", (filter_input(INPUT_POST, "id_instrutor", FILTER_SANITIZE_NUMBER_INT)));
                } else {
                    $dadosInstrutor = $Instrutor->search("fk_id_usuario", $idUsuario);
                    $instrutorAtual = $dadosInstrutor->id_instrutor;
                }

                $fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');
                $Usuario->setFoto($fotoAntiga);
                $Usuario->setNomeUsuario(filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING));
                $Usuario->setEmail(email: $email);
                $Usuario->setTipoUsuario($tipoUsuario);

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

                $Usuario->update('id_usuario', $idUsuario);
                // Envia e-mail avisando a academia sobre a alteração
                $Instrutor->setStatusValidacao('nao_validado');
                $Instrutor->update('id_instrutor', $instrutorAtual);
                $mensagem = "Há um novo pedido de validação dado de instrutor pendente.";
                $assunto = "Validação de Alteração de Dado de Instrutor";
                $enviado = $Instrutor->enviarValidacaoAcademia($instrutorAtual, $mensagem, $assunto);
            }

            if ($tipoUsuario === 'Administrador') {
                echo "<script>window.alert('Instrutor alterado com sucesso.');window.location.href='listaInstrutor.php';</script>";
                exit;
            }
            echo "<script>window.alert('Cadastro alterado com sucesso, a academia tem 72 horas para validar os novos dados.');window.location.href='instrutor.php';</script>";
            exit;
        } else {
            echo "<script> window.alert('Erro ao alterar o instrutor.');
        window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    if ($Instrutor->delete("id_instrutor", $id)) {
        header("location:listaInstrutor.php");
    } else {
        echo "<script>window.alert('Erro ao excluir'); window.open(document.referrer, '_self');</script>";
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
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <?php if ($tipoUsuario === 'Instrutor'):
        ?>
        <title>Conta</title><?php
    else:
        ?>
        <title>Cadastro de Instrutor</title><?php
    endif;
    ?>
</head>

<body>
    <?php if ($tipoUsuario != 'Administrador'):
        require_once "_parts/_navSite.php";
    else:
        require_once "_parts/_navAdmin.php";
    endif; ?>

    <main class="container cadastro">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtInstrutor = new Instrutor();
            $id = intval(filter_input(INPUT_POST, "id"));
            $instrutor = $edtInstrutor->search("id_instrutor", $id);
            $statusConta = $instrutor->status_validacao;
        } else {
            $instrutor = $Instrutor->search('fk_id_usuario', $idUsuario);
            $statusConta = $instrutor->status_validacao ?? null;
        }
        switch ($statusConta) {
            case "valido":
                $statusText = "Conta: Validada";
                $statusClass = "status-validada";
                break;
            case "invalido":
                $statusText = "Conta: Invalidada";
                $statusClass = "status-invalidada";
                break;
            default:
                $statusText = "Conta: Não validada";
                $statusClass = "status-pendente";
                break;
        }
        ?>

        <div class="status-badge <?= $statusClass ?>">
            <?= $statusText ?>
        </div>

        <form action="instrutor.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <?php if ($tipoUsuario === 'Instrutor'): ?>
                <h1 class="tituloh1">Conta</h1><?php
            else: ?>
                <h1 class="tituloh1">Cadastro de Instrutor</h1><?php
            endif; ?>

            <input type="hidden" value="<?php echo $instrutor->id_instrutor ?? null; ?>" name="id_instrutor">

            <!-- Alteração nos dados de usuario quando logado -->
            <?php if (session_status() === PHP_SESSION_ACTIVE && $tipoUsuario === 'Instrutor') {
                $usuario = $Usuario->search("id_usuario", $idUsuario); ?>
                <input type="hidden" name="fotoAntiga" value="<?php echo $usuario->foto ?? ''; ?>"></div>
                <div class="row gap-4 mb-3">
                    <div class="dadosUsuario col-md-6">
                        <div>
                            <label for="nome_usuario" class="form-label tituloDado">Nome de Usuário</label>
                            <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Digite o Nome do Usuário"
                                required class="form-control" value="<?php echo $usuario->nome_usuario ?? null; ?>">
                        </div>

                        <div class="usuarioAtleta">
                            <label for="email" class="form-label tituloDado">Email</label>
                            <input type="email" name="email" id="email" placeholder="Digite o Email do Usuário" required
                                class="form-control" value="<?php echo $usuario->email ?? null; ?>">
                        </div>

                        <div class="usuarioAtleta">
                            <label for="confirmaEmail" class="form-label tituloDado">Confirme o Email</label>
                            <input type="email" name="confirmaEmail" id="confirmaEmail"
                                placeholder="Digite a confirmação do E-mail" required class="form-control">
                            <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
                        </div>

                    </div>
                    <div class="fotoCadUsuario col-md-6">
                        <label for="foto" class="form-label tituloDado">Foto</label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="form-control" <?php echo empty($usuario->foto) ? 'required' : null ?>>
                        <img src="<?= !empty($usuario->foto) ? 'Images/usuario/' . $usuario->foto : 'Images\usuario\SemFoto.png' ?>"
                            alt="Foto de pedido de ajuda" class="mt-3 foto-usuario-cadastro" id="fotoColocada">
                    </div>
                </div>
            <?php } ?>

            <!-- Nome do Instrutor -->
            <?php if ($tipoUsuario === 'Administrador'): ?>
                <div class="mb-3">
                    <label for="nome_instrutor" class="form-label tituloDado">Nome do Instrutor</label>
                    <select name="nome_instrutor" class="form-select" id="nome_instrutor" required>
                        <option disabled <?= (!isset($instrutor->nome_instrutor)) ? 'selected' : '' ?>>Selecione o Instrutor
                        </option>

                        <?php
                        // Se estamos editando, mostra o nome atual do instrutor primeiro
                        if (isset($instrutor->nome_instrutor)):
                            ?>
                            <option value="<?= htmlspecialchars($instrutor->nome_instrutor) ?>" selected>
                                <?= htmlspecialchars($instrutor->nome_instrutor) ?>
                            </option>
                            <?php
                        endif;

                        // Exibe os demais usuários disponíveis
                        foreach ($usuariosDisponiveis as $uE):
                            // Evita repetir o nome do instrutor atual na lista
                            if (isset($instrutor->nome_instrutor) && $uE->nome_usuario === $instrutor->nome_instrutor)
                                continue;
                            ?>
                            <option value="<?= $uE->nome_usuario ?>">
                                <?= htmlspecialchars($uE->nome_usuario) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="col-6">
                <label for="telefone" class="form-label tituloDado">Telefone</label>
                <input type="text" name="telefone" id="telefone" placeholder="Digite o Telefone do Instrutor" required
                    class="form-control" value="<?php echo $instrutor->telefone ?? null; ?>">
            </div>

            <div class="col-6">
                <label for="fk_id_academia" class="form-label tituloDado">Academia</label>
                <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                    <option disabled <?= (!isset($instrutor->fk_id_academia)) ? 'selected' : '' ?>>Selecione a Academia
                    </option>
                    <?php foreach ($academia as $a): ?>
                        <option value="<?= $a->id_academia ?>" <?= (!empty($instrutor) && intval($instrutor->fk_id_academia ?? 0) === intval($a->id_academia)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a->nome_fantasia) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($tipoUsuario === 'Instrutor'): ?>
                <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn-padrao">
                        <?= isset($instrutor->id_instrutor) ? 'Atualizar' : 'Cadastrar' ?>
                    </button>
                    <button type="submit" name="btnExcluirConta" id="btnExcluirConta" class="btn btn-voltar">Excluir
                        Conta</button>
                </div>
            <?php elseif ($tipoUsuario === 'Administrador'): ?>
                <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn-padrao">Salvar</button>
                    <a href="listaInstrutor.php" class="btn btn-voltar">Voltar</a>
                </div>
            <?php endif; ?>
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
        $('#telefone').mask('(00) 00000-0000');
    </script>
    <!-- Foto -->
    <script>
        document.getElementById('foto').addEventListener('change', function (event) {
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