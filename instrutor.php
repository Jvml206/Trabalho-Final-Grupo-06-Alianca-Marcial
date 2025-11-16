<?php
$nivelPermitido = ['Administrador', 'Instrutor'];
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
    $Instrutor->setDataNascimento(filter_input(INPUT_POST, "data_nascimento", FILTER_SANITIZE_STRING));
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
        //Tenta adicionar e exibe a mensagem ao usuário
        if ($Instrutor->add()) {
            echo "<script>window.alert('Cadastro de instrutor realizado com sucesso.');window.location.href='index.php';window.location.href='instrutor.php';</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o instrutor.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Instrutor->update('id_instrutor', $id)) {

            if ($tipoUsuario === 'Instrutor') {

                $usuarioAtual = $Usuario->search("id_usuario", $idUsuario);

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
            }

            if ($tipoUsuario === 'Administrador') {
                echo "<script>window.alert('Instrutor alterado com sucesso.');window.location.href='listaInstrutor.php';</script>";
                exit;
            }
            echo "<script>window.alert('Instrutor alterado com sucesso.');window.location.href='instrutor.php';</script>";
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
    <link rel="stylesheet" href="CSS/baseSite.css">
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

    <main class="container">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtInstrutor = new Instrutor();
            $id = intval(filter_input(INPUT_POST, "id"));
            $instrutor = $edtInstrutor->search("id_instrutor", $id);
        }
        ?>

        <?php if ($tipoUsuario === 'Instrutor'):
                ?>
                <h2 class="text-center">Conta</h2><?php
            else:
                ?>
                <h2 class="text-center">Cadastro de Instrutor</h2><?php
            endif;
            ?>

        <form action="instrutor.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <input type="hidden" value="<?php echo $instrutor->id_instrutor ?? null; ?>" name="id_instrutor">

            <!-- Alteração nos dados de usuario quando logado -->
            <?php if (session_status() === PHP_SESSION_ACTIVE && $tipoUsuario === 'Instrutor') {
                $usuario = $Usuario->search("id_usuario", $idUsuario); ?>
                <input type="hidden" name="fotoAntiga" value="<?php echo $usuario->foto ?? ''; ?>"></div>
                <div class="row gap-4 mb-3">
                    <div class="dadosUsuario col-md-6">
                        <div>
                            <label for="nome_usuario" class="form-label">Nome de Usuário</label>
                            <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Digite o Nome do Usuário"
                                required class="form-control" value="<?php echo $usuario->nome_usuario ?? null; ?>">
                        </div>

                        <div class="usuarioAtleta">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" placeholder="Digite o Email do Usuário" required
                                class="form-control" value="<?php echo $usuario->email ?? null; ?>">
                        </div>

                        <div class="usuarioAtleta">
                            <label for="confirmaEmail" class="form-label">Confirme o Email</label>
                            <input type="email" name="confirmaEmail" id="confirmaEmail"
                                placeholder="Digite a confirmação do E-mail" required class="form-control">
                            <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
                        </div>

                    </div>
                    <div class="fotoCadUsuario col-md-6">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="form-control" <?php echo empty($usuario->foto) ? 'required' : null ?>>
                        <?php if (!empty($usuario->foto)): ?>
                            <img src="Images/usuario/<?php echo $usuario->foto; ?>" alt="Foto do Usuário"
                                class="mt-2 foto-usuario-cadastro">
                        <?php endif; ?>
                    </div>
                </div>
            <?php } ?>

            <!-- Nome do Instrutor -->
            <?php if ($tipoUsuario === 'Administrador'): ?>
                <div class="mb-3">
                    <label for="nome_instrutor" class="form-label">Nome do Instrutor</label>
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

            <div class="col-4">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                    value="<?php echo $instrutor->data_nascimento ?? null; ?>">
            </div>

            <div class="col-4">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone" placeholder="Digite o Telefone do Instrutor" required
                    class="form-control" value="<?php echo $instrutor->telefone ?? null; ?>">
            </div>

            <div class="col-4">
                <label for="fk_id_academia" class="form-label">Academia</label>
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
                <div class="mt-3 d-flex gap-2 mx-auto w-auto">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">
                        <?= isset($instrutor->id_instrutor) ? 'Atualizar' : 'Cadastrar' ?>
                    </button>
                    <button type="submit" name="btnExcluirConta" id="btnExcluirConta" class="btn btn-danger">Excluir
                        Conta</button>
                </div>
            <?php elseif ($tipoUsuario === 'Administrador'): ?>
                <div class="col-12 mt-3 d-flex gap-2 mx-auto">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                    <a href="listaInstrutor.php" class="btn btn-outline-danger">Voltar</a>
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
    <script src="JS/controleEmail.js"></script>
    <script>
        $('#telefone').mask('(00) 00000-0000');
    </script>
</body>

</html>