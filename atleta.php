<?php
$nivelPermitido = ['Administrador', 'Instrutor', 'Atleta'];
require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Academia = new Academia();
$academia = $Academia->all();
$Instrutor = new Instrutor();
$instrutor = $Instrutor->all();
$Atleta = new Atleta();
$Usuario = new Usuario();
$usuario = $Usuario->all();
$usuariosExistentes = $Usuario->usuariosAtletaExistentes();
$usuariosDisponiveis = $Usuario->usuariosAtletaDisponiveis();

$idUsuario = $_SESSION['user_id'];
$nome = $_SESSION['user_name'];
$tipoUsuario = $_SESSION['tipo_usuario'] ?? '';

// Se o usuário for "Atleta", verifica se já possui cadastro
if ($tipoUsuario === 'Atleta') {
    $existe = $Atleta->verificarPorUsuario($idUsuario);
    if ($existe) {
        // Se já existir cadastro, busca os dados para edição
        $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
    } else {
        // Caso contrário, cria um novo objeto vazio (cadastro novo)
        $Atleta = new Atleta();
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

    $Usuario = new Usuario();
    $nomeAtleta = trim(filter_input(INPUT_POST, "nome_atleta", FILTER_SANITIZE_STRING));

    // Busca o usuário pelo nome informado
    $usuarioEncontrado = $Usuario->searchString("nome_usuario", $nomeAtleta);
    $fkUsuario = $Usuario->search("id_usuario", $idUsuario);

    if ($tipoUsuario === 'Administrador') {
        $Atleta->setFkIdUsuario($fkUsuario);
        $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setFkIdInstrutor(filter_input(INPUT_POST, "fk_id_instrutor", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setNomeAtleta($nomeAtleta);

    } elseif ($tipoUsuario === 'Instrutor') {
        $Instrutor = new Instrutor();
        $dadosInstrutor = $Instrutor->search("fk_id_usuario", $idUsuario);

        if (!$dadosInstrutor) {
            echo "<script>window.alert('Erro: dados do instrutor não encontrados.'); window.history.back();</script>";
            exit;
        }

        $Atleta->setNomeAtleta($nomeAtleta);
        $Atleta->setFkIdUsuario($fkUsuario);
        $Atleta->setFkIdInstrutor($dadosInstrutor->id_instrutor);
        $Atleta->setFkIdAcademia($dadosInstrutor->fk_id_academia);

    } elseif ($tipoUsuario === 'Atleta') {
        $Atleta->setNomeAtleta($nome);
        $Atleta->setFkIdUsuario($idUsuario);
        $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setFkIdInstrutor(filter_input(INPUT_POST, "fk_id_instrutor", FILTER_SANITIZE_NUMBER_INT));
    }

    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($Atleta->add()) {
            echo "<script>window.alert('Cadastro de atleta realizado com sucesso.');window.location.href=atleta.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o atleta.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($tipoUsuario === 'Atleta') {

            $Usuario = new Usuario();
            $usuarioAtual = $Usuario->search("id_usuario", $idUsuario);

            $fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');

            $Usuario->setFoto($fotoAntiga);
            $Usuario->setNomeUsuario(filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING));
            $Usuario->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
            $Usuario->setTipoUsuario($tipoUsuario);

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($extensao, $permitidas)) {
                    $nomeFoto = uniqid("usuario_") . "." . $extensao;
                    $destino = "Images/usuario/" . $nomeFoto;
                    $caminhoAntigo = "Images/usuario/" . $fotoAntiga;

                    if (!empty($fotoAntiga) && is_file($caminhoAntigo)) {
                        var_dump($fotoAntiga, $caminhoAntigo, file_exists($caminhoAntigo));
                        unlink($caminhoAntigo);
                    }

                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                        $Usuario->setFoto($nomeFoto);
                    }
                }
            }

            if ($Usuario->update('id_usuario', $idUsuario)) {
                $Atleta->update('id_atleta', $id);
                echo "<script>window.alert('Atleta alterado com sucesso.'); 
                window.location.href='atleta.php';</script>";
                exit;
            }
            echo "<script>window.alert('Atleta alterado com sucesso.'); 
            window.location.href='listaAtleta.php';</script>";
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
    <title>Cadastro de Atleta</title>
</head>

<body>
    <?php if (isset($_SESSION['tipo_usuario'])):
        $tipoUsuario = $_SESSION['tipo_usuario'];

        if ($tipoUsuario != 'Administrador'):
            require_once "_parts/_navSite.php";
        else:
            require_once "_parts/_navAdmin.php";
        endif;
    endif;
    ?>


    <main class="container">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $id = intval(filter_input(INPUT_POST, "id"));
            $dadosAtleta = $Atleta->search("id_atleta", $id);
        }
        if ($tipoUsuario === 'Instrutor') {
            // Busca a academia do instrutor
            $dadosInstrutor = $Instrutor->search("fk_id_usuario", $idUsuario);
            $idAcademiaInstrutor = $dadosInstrutor->fk_id_academia ?? null;
        }
        ?>

        <form action="atleta.php" method="post" class="row g3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">

            <h2 class="text-center">Cadastro de Atleta</h2>
            <input type="hidden" value="<?php echo $dadosAtleta->id_atleta ?? null; ?>" name="id_atleta">

            <!-- Alteração nos dados de usuario quando logado -->
            <?php if (session_status() === PHP_SESSION_ACTIVE && $tipoUsuario === 'Atleta') {
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

            <!-- Nome do Atleta -->
            <?php if ($tipoUsuario === 'Administrador' || $tipoUsuario === 'Instrutor'): ?>
                <div class="mb-3">
                    <label for="nome_atleta" class="form-label">Nome do Atleta</label>
                    <select name="nome_atleta" class="form-select" id="nome_atleta" required>
                        <option disabled <?= (!isset($dadosAtleta->nome_atleta)) ? 'selected' : '' ?>>Selecione o Atleta
                        </option>

                        <?php
                        // Se estamos editando, mostra o nome atual do atleta primeiro
                        if (isset($dadosAtleta->nome_atleta)):
                            ?>
                            <option value="<?= htmlspecialchars($dadosAtleta->nome_atleta) ?>" selected>
                                <?= htmlspecialchars($dadosAtleta->nome_atleta) ?>
                            </option>
                            <?php
                        endif;

                        // Exibe os demais usuários disponíveis (que ainda não são atletas)
                        foreach ($usuariosDisponiveis as $uE):
                            // Evita repetir o nome do atleta atual na lista
                            if (isset($dadosAtleta->nome_atleta) && $uE->nome_usuario === $dadosAtleta->nome_atleta)
                                continue;
                            ?>
                            <option value="<?= $uE->nome_usuario ?>">
                                <?= htmlspecialchars($uE->nome_usuario) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Nome da Academia -->
            <?php if ($tipoUsuario === 'Administrador'): ?>
                <div class="col-md-6">
                    <label for="fk_id_academia" class="form-label">Academia</label>
                    <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                        <option disabled <?= (!isset($dadosAtleta->fk_id_academia)) ? 'selected' : '' ?>>Selecione a Academia
                        </option>
                        <?php foreach ($academia as $ac): ?>
                            <option value="<?= $ac->id_academia ?>">
                                <?= htmlspecialchars($ac->nome_fantasia) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <?php elseif ($tipoUsuario === 'Atleta'): ?>
                <div class="col-md-6">
                    <label for="fk_id_academia" class="form-label">Academia</label>
                    <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                        <option disabled <?= (!isset($dadosAtleta->fk_id_academia)) ? 'selected' : '' ?>>Selecione a Academia
                        </option>
                        <?php foreach ($academia as $ac): ?>
                            <option value="<?= $ac->id_academia ?>">
                                <?= htmlspecialchars($ac->nome_fantasia) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <?php elseif ($tipoUsuario === 'Instrutor'): ?>
                <input type="hidden" name="fk_id_academia" value="<?= $idAcademiaInstrutor ?>">
            <?php endif; ?>

            <!-- Nome do Instrutor -->
            <?php if ($tipoUsuario === 'Administrador' || $tipoUsuario === 'Atleta'): ?>
                <div class="col-md-6">
                    <label for="fk_id_instrutor" class="form-label">Instrutor</label>
                    <select name="fk_id_instrutor" class="form-select" id="fk_id_instrutor" required>
                        <option disabled <?= (!isset($dadosAtleta->fk_id_instrutor)) ? 'selected' : '' ?>>Selecione o
                            Instrutor</option>
                        <?php foreach ($instrutor as $ins): ?>
                            <option value="<?= $ins->id_instrutor ?>">
                                <?= htmlspecialchars($ins->nome_instrutor) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php elseif ($tipoUsuario === 'Instrutor'): ?>
                <input type="hidden" name="fk_id_instrutor" value="<?= $dadosInstrutor->id_instrutor ?>">
            <?php endif; ?>

            <div class="col-md-6">
                <label for="esporte" class="form-label">Esporte</label>
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
                <label for="categoria" class="form-label">Categoria</label>
                <input type="text" name="categoria" id="categoria" placeholder="Digite a categoria do Atleta" required
                    class="form-control" value="<?php echo $dadosAtleta->categoria ?? null; ?>">
            </div>

            <div class="col-md-4">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                    value="<?php echo $dadosAtleta->data_nascimento ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="sexo" class="form-label">Sexo</label>
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
                <label for="peso" class="form-label">Peso</label>
                <input type="text" name="peso" id="peso"
                    placeholder="Digite o peso. Ex.:102.65 (Coloque as casas depois do ponto)" required
                    class="form-control" value="<?php echo $dadosAtleta->peso ?? null; ?>">
            </div>


            <div class="col-12">
                <label for="biografia" class="form-label">Biografia</label>
                <textarea type="text" name="biografia" id="biografia"
                    placeholder="Digite uma biografia, no mínimo 200 caracteres" required minlength="200"
                    class="form-control"><?php echo $dadosAtleta->biografia ?? null; ?></textarea>
            </div>

            <?php if ($tipoUsuario === 'Atleta'): ?>
                <div class="col-12 mt-3 d-flex gap-2">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom mx-auto">
                        <?= isset($dadosAtleta->id_atleta) ? 'Atualizar' : 'Cadastrar' ?>
                    </button>
                </div>
            <?php elseif ($tipoUsuario === 'Administrador' || $tipoUsuario === 'Instrutor'): ?>
                <div class="col-12 mt-3 d-flex gap-2 mx-auto">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                    <a href="listaAtleta.php" class="btn btn-outline-danger">Voltar</a>
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
        $('#peso').mask('000.00', { reverse: true });
    </script>
</body>

</html>