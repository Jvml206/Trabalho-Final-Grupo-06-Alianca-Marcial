<?php
$nivelPermitido = ['Administrador', 'Atleta'];
require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Academia = new Academia();
$academia = $Academia->all();
$Instrutor = new Instrutor();
$instrutor = $Instrutor->instrutoresValidos();
$Atleta = new Atleta();
$Usuario = new Usuario();
$usuario = $Usuario->all();
$usuariosDisponiveis = $Usuario->usuariosAtletaDisponiveis();

$idUsuario = $_SESSION['user_id'];
$nome = $_SESSION['user_name'];
$email = $_SESSION['user_email'];
$tipoUsuario = $_SESSION['tipo_usuario'];

// Se o usuário for "Atleta", verifica se já possui cadastro
if ($tipoUsuario === 'Atleta') {
    $existe = $Atleta->verificarPorUsuario($idUsuario);
    if ($existe) {
        // Se já existir cadastro, busca os dados para edição
        $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
    }
}


if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Atleta->setDataNascimento(filter_input(INPUT_POST, "data_nascimento", FILTER_SANITIZE_STRING));
    $Atleta->setBiografia(filter_input(INPUT_POST, "biografia", FILTER_SANITIZE_STRING));
    $Atleta->setSexo(filter_input(INPUT_POST, "sexo", FILTER_SANITIZE_STRING));
    $Atleta->setEsporte(filter_input(INPUT_POST, "esporte", FILTER_SANITIZE_STRING));
    $Atleta->setPeso(filter_input(INPUT_POST, "peso"));
    $Atleta->setCategoria(filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id_atleta');


    if ($tipoUsuario === 'Administrador') {
        $nomeAtleta = trim(filter_input(INPUT_POST, "nome_atleta", FILTER_SANITIZE_STRING));
        $usuarioEncontrado = $Usuario->searchAtleta("nome_usuario", $nomeAtleta);
        $fkUsuario = $Usuario->search("id_usuario", $usuarioEncontrado->id_usuario);

        $Atleta->setFkIdUsuario($fkUsuario->id_usuario);
        $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setFkIdInstrutor(filter_input(INPUT_POST, "fk_id_instrutor", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setNomeAtleta($nomeAtleta);

    } elseif ($tipoUsuario === 'Atleta') {
        $Atleta->setNomeAtleta($nome);
        $Atleta->setFkIdUsuario($idUsuario);
        $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setFkIdInstrutor(filter_input(INPUT_POST, "fk_id_instrutor", FILTER_SANITIZE_NUMBER_INT));
    }

    if (empty($id)):
        if ($Atleta->add()) {

            // pega o id do atleta recém inserido
            $idAtleta = $Atleta->lastId();

            // envia o email ao instrutor
            $mensagem = "Por favor, valide o cadastro clicando no link abaixo:";
            $assunto = "Validação de novo atleta";
            $Atleta->enviarValidacaoInstrutor($idAtleta, $mensagem, $assunto);

            echo "<script>
            window.alert('Cadastro realizado! Aguarde o instrutor validar a conta.');
            window.location.href='atleta.php';
          </script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o atleta.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Atleta->update('id_atleta', $id)) {

            if ($tipoUsuario === 'Atleta') {

                $usuarioAtual = $Usuario->search("id_usuario", $idUsuario);
                if ($tipoUsuario === 'Administrador') {
                    $atletaAtual = $Atleta->search("id_atleta", (filter_input(INPUT_POST, "id_atleta", FILTER_SANITIZE_NUMBER_INT)));
                } else {
                    $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
                    $atletaAtual = $dadosAtleta->id_atleta;
                }

                $fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');
                $Usuario->setFoto($fotoAntiga);
                $Usuario->setNomeUsuario(filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING));
                $Usuario->setEmail($email);
                $Usuario->setTipoUsuario($tipoUsuario);

                // Upload da nova foto
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
                // Envia e-mail avisando o instrutor sobre a alteração
                $Atleta->setStatusValidacao('nao_validado');
                $Atleta->update('id_atleta', $atletaAtual);
                $mensagem = "Há um novo pedido de validação dado de atleta pendente.";
                $assunto = "Validação de Alteração de Dado de Atleta";
                $enviado = $Atleta->enviarValidacaoInstrutor($atletaAtual, $mensagem, $assunto);
            }

            if ($tipoUsuario === 'Administrador') {
                echo "<script>window.alert('Atleta alterado com sucesso.');window.location.href='listaAtleta.php';</script>";
                exit;
            }
            echo "<script>window.alert('Cadastro alterado com sucesso, o instrutor tem 72 horas para validar os novos dados.');window.location.href='atleta.php';</script>";
            exit;
        } else {
            echo "<script> window.alert('Erro ao alterar o atleta.');
        window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    if ($Atleta->delete("id_atleta", $id)) {
        header("location:listaAtleta.php");
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
    <?php if ($tipoUsuario === 'Atleta'):
        ?>
        <title>Conta</title><?php
    else:
        ?>
        <title>Cadastro de Atleta</title><?php
    endif;
    ?>
</head>

<body>
    <?php if ($tipoUsuario != 'Administrador'):
        require_once "_parts/_navSite.php";
    else:
        require_once "_parts/_navAdmin.php";
    endif;
    ?>

    <main class="container cadastro">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $id = intval(filter_input(INPUT_POST, "id"));
            $dadosAtleta = $Atleta->search("id_atleta", $id);
        }
        ?>

        <form action="atleta.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <?php if ($tipoUsuario === 'Atleta'):
                ?>
                <h1 class="tituloh1">Conta</h1><?php
            else:
                ?>
                <h1 class="tituloh1">Cadastro de Atleta</h1><?php
            endif;
            ?>
            <input type="hidden" value="<?php echo $dadosAtleta->id_atleta ?? null; ?>" name="id_atleta">

            <!-- Alteração nos dados de usuario quando logado -->
            <?php if (session_status() === PHP_SESSION_ACTIVE && $tipoUsuario === 'Atleta') {
                $usuario = $Usuario->search("id_usuario", $idUsuario); ?>
                <input type="hidden" name="fotoAntiga" value="<?php echo $usuario->foto ?? ''; ?>">
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
                        <?php if (!empty($usuario->foto)): ?>
                            <img src="Images/usuario/<?php echo $usuario->foto; ?>" alt="Foto do Usuário"
                                class="mt-3 foto-usuario-cadastro">
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ($tipoUsuario === 'Administrador'): ?>
                <!-- Nome do Atleta -->
                <div class="mb-3">
                    <label for="nome_atleta" class="form-label tituloDado">Nome do Atleta</label>
                    <select name="nome_atleta" class="form-select" id="nome_atleta" required>
                        <option disabled <?= (!isset($dadosAtleta->nome_atleta)) ? 'selected' : '' ?>>Selecione o Atleta
                        </option>

                        <?php
                        if (isset($dadosAtleta->nome_atleta)):
                            ?>
                            <option value="<?= htmlspecialchars($dadosAtleta->nome_atleta) ?>" selected>
                                <?= htmlspecialchars($dadosAtleta->nome_atleta) ?>
                            </option>
                            <?php
                        endif;

                        foreach ($usuariosDisponiveis as $uE):
                            if (isset($dadosAtleta->nome_atleta) && $uE->nome_usuario === $dadosAtleta->nome_atleta)
                                continue;
                            ?>
                            <option value="<?= $uE->nome_usuario ?>">
                                <?= htmlspecialchars($uE->nome_usuario) ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>
            </div>

            <!-- Nome da Academia -->
            <div class="col-md-6">
                <label for="fk_id_academia" class="form-label tituloDado">Academia</label>
                <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                    <option value="" disabled <?= (!isset($dadosAtleta->fk_id_academia)) ? 'selected' : '' ?>>Selecione a
                        Academia
                    </option>
                    <?php foreach ($academia as $ac): ?>
                        <option value="<?= $ac->id_academia ?>" <?= (($dadosAtleta->fk_id_academia ?? null) == $ac->id_academia) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ac->nome_fantasia) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nome do Instrutor -->
            <div class="col-md-6">
                <label for="fk_id_instrutor" class="form-label tituloDado">Instrutor</label>
                <select name="fk_id_instrutor" class="form-select" id="fk_id_instrutor" required disabled>
                    <option value="" disabled <?= (!isset($dadosAtleta->fk_id_instrutor)) ? 'selected' : '' ?>>
                        Selecione o Instrutor
                    </option>
                    <?php foreach ($instrutor as $ins): ?>
                        <?php if ($ins->status_validacao === 'valido'): ?>
                            <option value="<?= $ins->id_instrutor ?>" data-academia="<?= $ins->fk_id_academia ?>"
                                <?= (($dadosAtleta->fk_id_instrutor ?? null) == $ins->id_instrutor) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ins->nome_instrutor) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="esporte" class="form-label tituloDado">Esporte</label>
                <select id="esporte" name="esporte" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($dadosAtleta->esporte)) ? 'selected' : '' ?>>Selecione o Esporte
                    </option>
                    <option value="Aikidô" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Aikidô') ? 'selected' : '' ?>>Aikidô</option>
                    <option value="Boxe" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Boxe') ? 'selected' : '' ?>>
                        Boxe</option>
                    <option value="Capoeira" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Capoeira') ? 'selected' : '' ?>>Capoeira</option>
                    <option value="Jiu-Jitsu Brasileiro" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Jiu-Jitsu Brasileiro') ? 'selected' : '' ?>>Jiu-Jitsu Brasileiro
                    </option>
                    <option value="Judô" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Judô') ? 'selected' : '' ?>>
                        Judô</option>
                    <option value="Karatê" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Karatê') ? 'selected' : '' ?>>Karatê</option>
                    <option value="Kung Fu" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Kung Fu') ? 'selected' : '' ?>>Kung Fu</option>
                    <option value="MMA" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'MMA') ? 'selected' : '' ?>>MMA
                    </option>
                    <option value="Muay Thai" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Muay Thai') ? 'selected' : '' ?>>Muay Thai</option>
                    <option value="Taekwondo" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Taekwondo') ? 'selected' : '' ?>>Taekwondo</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="categoria" class="form-label tituloDado">Categoria</label>
                <input type="text" name="categoria" id="categoria" placeholder="Digite a categoria do Atleta" required
                    class="form-control" value="<?php echo $dadosAtleta->categoria ?? null; ?>">
            </div>

            <div class="col-md-4">
                <label for="data_nascimento" class="form-label tituloDado">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                    value="<?php echo $dadosAtleta->data_nascimento ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="sexo" class="form-label tituloDado">Sexo</label>
                <select id="sexo" name="sexo" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($dadosAtleta->sexo)) ? 'selected' : '' ?>>Selecione o sexo</option>
                    <option value="F" <?= (isset($dadosAtleta->sexo) && $dadosAtleta->sexo == 'F') ? 'selected' : '' ?>>
                        Feminino
                    </option>
                    <option value="M" <?= (isset($dadosAtleta->sexo) && $dadosAtleta->sexo == 'M') ? 'selected' : '' ?>>
                        Masculino
                    </option>
                </select>
            </div>

            <div class="col-md-5">
                <label for="peso" class="form-label tituloDado">Peso</label>
                <input type="text" name="peso" id="peso"
                    placeholder="Digite o peso. Ex.:102.65 (Coloque as casas depois do ponto)" required
                    class="form-control" value="<?php echo $dadosAtleta->peso ?? null; ?>">
            </div>


            <div class="col-12">
                <label for="biografia" class="form-label tituloDado">Biografia</label>
                <textarea type="text" name="biografia" id="biografia"
                    placeholder="Digite uma biografia, no mínimo 200 caracteres" required minlength="200"
                    class="form-control"><?php echo $dadosAtleta->biografia ?? null; ?></textarea>
            </div>

            <?php if ($tipoUsuario === 'Atleta'): ?>
                <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-padrao">
                        <?= isset($dadosAtleta->id_atleta) ? 'Atualizar' : 'Cadastrar' ?>
                    </button>
                    <button type="submit" name="btnExcluirConta" id="btnExcluirConta" class="btn btn-danger">Excluir
                        Conta</button>
                </div>
            <?php elseif ($tipoUsuario === 'Administrador'): ?>
                <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-padrao">Salvar</button>
                    <a href="listaAtleta.php" class="btn btn-voltar">Voltar</a>
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
    <script src="JS/instrutorDoAtleta.js"></script>
    <script>
        $('#peso').mask('000.00', { reverse: true });
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