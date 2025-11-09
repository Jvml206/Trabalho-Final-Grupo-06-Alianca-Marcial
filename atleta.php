<?php
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
$usuariosExistentes = $Usuario->usuariosAtletaExistentes();

$idUsuario = $_SESSION['user_id'];
$nome = $_SESSION['user_name'];
$tipoUsuario = $_SESSION['tipo_usuario'] ?? '';

// Se o usuário for "Atleta", verifica se já possui cadastro
if ($tipoUsuario === 'Atleta') {
    $existe = $Atleta->verificarPorUsuario($idUsuario);
    if ($existe) {
        echo "<script>window.alert('Você já possui um cadastro de atleta e não pode criar outro.'); window.history.back();</script>";
        exit;
    }
}

// Bloqueia o acesso se for "Usuário"
if ($tipoUsuario === 'Usuário') {
    echo "<script>window.alert('Você não tem permissão para acessar esta página.'); window.location.href='index.php';</script>";
    exit;
}

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Atleta->setDataNascimento(filter_input(INPUT_POST, "data_nascimento", FILTER_SANITIZE_STRING));
    $Atleta->setBiografia(filter_input(INPUT_POST, "biografia", FILTER_SANITIZE_STRING));
    $Atleta->setSexo(filter_input(INPUT_POST, "sexo", FILTER_SANITIZE_STRING));
    $Atleta->setEsporte(filter_input(INPUT_POST, "esporte", FILTER_SANITIZE_STRING));
    $Atleta->setPeso(filter_input(INPUT_POST, "peso"));
    $Atleta->setCategoria(filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id_atleta');

    // Regras conforme o tipo de acesso
    if ($tipoUsuario === 'Administrador') {
        $Usuario = new Usuario();
        $nomeAtleta = trim(filter_input(INPUT_POST, "nome_atleta", FILTER_SANITIZE_STRING));

        // Verifica se o usuário existe
        $usuarioExiste = $Usuario->searchString("nome_usuario", $nomeAtleta);
        if (!$usuarioExiste) {
            echo "<script>window.alert('Usuário informado não existe.'); window.history.back();</script>";
            exit;
        }

        // Se o usuário existir, continua o cadastro
        $Atleta->setFkIdUsuario($id_usuario);
        $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setFkIdInstrutor(filter_input(INPUT_POST, "fk_id_instrutor", FILTER_SANITIZE_NUMBER_INT));
        $Atleta->setNomeAtleta($nomeAtleta);

    } elseif ($tipoUsuario === 'Instrutor') {
        $Usuario = new Usuario();
        $Instrutor = new Instrutor();
        $dadosInstrutor = $Instrutor->search("fk_id_usuario", $idUsuario);
        $nomeAtleta = trim(filter_input(INPUT_POST, "nome_atleta", FILTER_SANITIZE_STRING));

        // Verifica se o usuário existe
        $usuarioExiste = $Usuario->searchString("nome_usuario", $nomeAtleta);
        if (!$usuarioExiste) {
            echo "<script>window.alert('Usuário informado não existe.'); window.history.back();</script>";
            exit;
        }

        if (!$dadosInstrutor) {
            echo "<script>window.alert('Erro: dados do instrutor não encontrados.'); window.history.back();</script>";
            exit;
        }

        $Atleta->setNomeAtleta($nomeAtleta);
        $Atleta->setFkIdUsuario($usuarioExiste->id_usuario);
        $Atleta->setFkIdInstrutor($dadosInstrutor->id_instrutor);
        $Atleta->setFkIdAcademia($dadosInstrutor->fk_id_academia);

        // Impedir que o instrutor cadastre um atleta com usuário já usado
        if ($Atleta->verificarPorUsuario($idUsuarioNovo)) {
            echo "<script>window.alert('Já existe um atleta vinculado a este usuário.'); window.history.back();</script>";
            exit;
        }
    } elseif ($tipoUsuario === 'Atleta') {
        // Atleta só pode cadastrar um, ele mesmo
        if (empty($id) && $Atleta->verificarPorUsuario($idUsuario)) {
            echo "<script>window.alert('Você já está cadastrado.'); window.location.href='listaAtleta.php';</script>";
            exit;
        }
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
        if ($Atleta->update('id_atleta', $id)) {
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
    <?php require_once "_parts/_navAdmin.php"; ?>
    <main class="container">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $id = intval(filter_input(INPUT_POST, "id"));
            $Atleta = $Atleta->search("id_atleta", $id);
        }
        if ($tipoUsuario === 'Instrutor') {
            // Busca a academia do instrutor
            $dadosInstrutor = $Instrutor->search("fk_id_usuario", $idUsuario);
            $idAcademiaInstrutor = $dadosInstrutor->fk_id_academia ?? null;
        }
        ?>

        <h2 class="text-center">Cadastro de Atleta</h2>

        <form action="atleta.php" method="post" class="row g3 mt-3">

            <input type="hidden" value="<?php echo $Atleta->id_atleta ?? null; ?>" name="id_atleta">

            <!-- Nome do Atleta -->
            <?php if ($tipoUsuario === 'Administrador' || $tipoUsuario === 'Instrutor'): ?>
                <div class="mb-3">
                    <label for="nome_atleta" class="form-label">Nome do Atleta</label>
                    <input type="text" name="nome_atleta" id="nome_atleta" class="form-control"
                        placeholder="Digite o nome do atleta" required>
                    <div id="mensagemUsuario" class="mt-2"></div>
                </div>
            <?php endif; ?>

            <!-- Nome da Academia -->
            <?php if ($tipoUsuario === 'Administrador' || $tipoUsuario === 'Atleta'): ?>
                <div class="col-md-6">
                    <label for="fk_id_academia" class="form-label">Academia</label>
                    <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                        <option disabled selected>Selecione a Academia</option>
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
                        <option disabled selected>Selecione o Instrutor</option>
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
                    <option disabled <?= (!isset($Atleta->esporte)) ? 'selected' : '' ?>>Selecione o Esporte</option>
                    <option value="Aikidô" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Aikidô') ? 'selected' : '' ?>>Aikidô</option>
                    <option value="Boxe" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Boxe') ? 'selected' : '' ?>>
                        Boxe</option>
                    <option value="Capoeira" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Capoeira') ? 'selected' : '' ?>>Capoeira</option>
                    <option value="Jiu-Jitsu Brasileiro" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Jiu-Jitsu Brasileiro') ? 'selected' : '' ?>>Jiu-Jitsu Brasileiro</option>
                    <option value="Judô" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Judô') ? 'selected' : '' ?>>
                        Judô</option>
                    <option value="Karatê" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Karatê') ? 'selected' : '' ?>>Karatê</option>
                    <option value="Kung Fu" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Kung Fu') ? 'selected' : '' ?>>Kung Fu</option>
                    <option value="MMA" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'MMA') ? 'selected' : '' ?>>MMA
                    </option>
                    <option value="Muay Thai" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Muay Thai') ? 'selected' : '' ?>>Muay Thai</option>
                    <option value="Taekwondo" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Taekwondo') ? 'selected' : '' ?>>Taekwondo</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="categoria" class="form-label">Categoria</label>
                <input type="text" name="categoria" id="categoria" placeholder="Digite a categoria do Atleta" required
                    class="form-control" value="<?php echo $Atleta->categoria ?? null; ?>">
            </div>

            <div class="col-md-4">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                    value="<?php echo $Atleta->data_nascimento ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select id="sexo" name="sexo" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($Atleta->sexo)) ? 'selected' : '' ?>>Selecione o sexo</option>
                    <option value="F" <?= (isset($Atleta->sexo) && $Atleta->sexo == 'F') ? 'selected' : '' ?>>Feminino
                    </option>
                    <option value="M" <?= (isset($Atleta->sexo) && $Atleta->sexo == 'M') ? 'selected' : '' ?>>Masculino
                    </option>
                </select>
            </div>

            <div class="col-md-5">
                <label for="peso" class="form-label">Peso</label>
                <input type="text" name="peso" id="peso" placeholder="Digite o peso. Ex.:102.65 (Coloque as casas depois do ponto)" required
                    class="form-control" value="<?php echo $Atleta->peso ?? null; ?>">
            </div>


            <div class="col-12">
                <label for="biografia" class="form-label">Biografia</label>
                <textarea type="text" name="biografia" id="biografia"
                    placeholder="Digite uma biografia, no mínimo 200 caracteres" required minlength="200"
                    class="form-control"><?php echo $Atleta->biografia ?? null; ?></textarea>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                <a href="listaAtleta.php" class="btn btn-outline-danger">Voltar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="JS/verificaUsuario.js"></script>
    <script>
        $('#peso').mask('000,00', { reverse: true });
        const usuariosExistentes = <?php echo json_encode($usuariosExistentes); ?>;
        configurarVerificacaoUsuario(usuariosExistentes);
    </script>
</body>

</html>