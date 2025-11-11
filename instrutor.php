<?php
$nivelPermitido = ['Administrador', 'Instrutor'];
require_once 'validaUser.php';

$idUsuario = $_SESSION['user_id'];
$nome = $_SESSION['user_name'];
$email = $_SESSION['user_email'];

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Instrutor = new Instrutor();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Instrutor->setNomeInstrutor($nome);
    $Instrutor->setDataNascimento(filter_input(INPUT_POST, "data_nascimento", FILTER_SANITIZE_STRING));
    $Instrutor->setTelefone(filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_STRING));
    $Instrutor->setEmail($email);
    $Instrutor->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
    $Instrutor->setFkIdUsuario($idUsuario);
    $id = filter_input(INPUT_POST, 'id_instrutor');
    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($Instrutor->add()) {
            echo "<script>window.alert('Cadastro de instrutor realizado com sucesso.');window.location.href=instrutor.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o instrutor.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Instrutor->update('id_instrutor', $id)) {
            echo "<script>window.alert('Instrutor alterado com sucesso.'); 
            window.location.href='listaInstrutor.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar o Instrutor.');
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
    <title>Cadastro de Instrutor</title>
</head>

<body>
    <?php if (isset($_SESSION['tipo_usuario'])):
        $tipoUsuario = $_SESSION['tipo_usuario'];

        if ($tipoUsuario != 'Administrador'):
            require_once "_parts/_navSite.php";
        else:
            require_once "_parts/_navAdmin.php";
        endif;
    endif; ?>
    
    <main class="container">
        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        $Academia = new Academia();
        $academia = $Academia->all();

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtInstrutor = new Instrutor();
            $id = intval(filter_input(INPUT_POST, "id"));
            $Instrutor = $edtInstrutor->search("id_instrutor", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Instrutor</h2>

        <form action="instrutor.php" method="post" class="row g3 mt-3" id="form_valida_email">

            <input type="hidden" value="<?php echo $Instrutor->id_instrutor ?? null; ?>" name="id_instrutor">

            <div class="col-md-6">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                    value="<?php echo $Instrutor->data_nascimento ?? null; ?>">
            </div>

            <div class="col-6">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" name="telefone" id="telefone" placeholder="Digite o Telefone do Instrutor" required
                    class="form-control" value="<?php echo $Instrutor->telefone ?? null; ?>">
            </div>

            <div class="col-6">
                <label for="fk_id_academia" class="form-label">Academia</label>
                <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                    <option disabled <?= (!isset($Instrutor->fk_id_academia)) ? 'selected' : '' ?>>Selecione a Academia
                    </option>
                    <?php foreach ($academia as $a): ?>
                        <option value="<?= $a->id_academia ?>" <?= (!empty($Instrutor) && intval($Instrutor->fk_id_academia ?? 0) === intval($a->id_academia)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a->nome_fantasia) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                <a href="listaInstrutor.php" class="btn btn-outline-danger">Voltar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>