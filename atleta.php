<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Atleta = new Atleta();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Atleta->setNomeAtleta(filter_input(INPUT_POST, "nome_atleta", FILTER_SANITIZE_STRING));
    $Atleta->setDataNascimento(filter_input(INPUT_POST, "data_nascimento", FILTER_SANITIZE_STRING));
    $Atleta->setBiografia(filter_input(INPUT_POST, "biografia", FILTER_SANITIZE_STRING));
    $Atleta->setSexo(filter_input(INPUT_POST, "sexo", FILTER_SANITIZE_STRING));
    $Atleta->setEsporte(filter_input(INPUT_POST, "esporte", FILTER_SANITIZE_NUMBER_INT));
    $Atleta->setPeso(filter_input(INPUT_POST, "peso", FILTER_SANITIZE_NUMBER_INT));
    $Atleta->setCategoria(filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_NUMBER_INT));
    $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
    $Atleta->setFkIdUsuario(filter_input(INPUT_POST, "fk_id_usuario", FILTER_SANITIZE_NUMBER_INT));
    $id = filter_input(INPUT_POST, 'id_atleta');

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
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        $Academia = new Academia();
        $academia = $Academia->all();

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtAtleta = new Atleta();
            $id = intval(filter_input(INPUT_POST, "id"));
            $Atleta = $edtAtleta->search("id_atleta", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Atleta</h2>

        <form action="atleta.php" method="post" class="row g3 mt-3">

            <input type="hidden" value="<?php echo $Atleta->id_atleta ?? null; ?>" name="id_atleta">

            <div class="col-md-6 mb-3">
                <label for="nome_atleta" class="form-label">Nome</label>
                <input type="text" name="nome_atleta" id="nome_atleta" placeholder="Digite o Nome do Atleta"
                    required class="form-control" value="<?php echo $Atleta->nome_atleta ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                    value="<?php echo $Atleta->data_nascimento ?? null; ?>">
            </div>

            <div class="col-12">
                <label for="biografia" class="form-label">Biografia</label>
                <textarea type="text" name="biografia" id="biografia" placeholder="Digite uma biografia do Atleta" required
                    class="form-control" value="<?php echo $Atleta->biografia ?? null; ?>"></textarea>
            </div>

            <div class="col-md-6">
                <label for="sexo" class="form-label">Sexo</label>
                <select id="sexo" name="sexo" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($Atleta->sexo)) ? 'selected' : '' ?>>Selecione o sexo</option>
                    <option value="F" <?= (isset($Atleta->sexo) && $Atleta->sexo == 'F') ? 'selected' : '' ?>>Feminino
                    </option>
                    <option value="M" <?= (isset($Atleta->sexo) && $Atleta->sexo == 'M') ? 'selected' : '' ?>>Masculino
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="esporte" class="form-label">Esporte</label>
                <select id="esporte" name="esporte" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($Atleta->esporte)) ? 'selected' : '' ?>>Selecione o Esporte</option>
                    <option value="Aikidô" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Aikidô') ? 'selected' : '' ?>>Aikidô
                    </option>
                    <option value="Boxe" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Boxe') ? 'selected' : '' ?>>Boxe
                    </option>
                    <option value="Capoeira" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Capoeira') ? 'selected' : '' ?>>Capoeira
                    </option>
                    <option value="Jiu-Jitsu Brasileiro" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Jiu-Jitsu Brasileiro') ? 'selected' : '' ?>>Jiu-Jitsu Brasileiro
                    </option>
                    <option value="Judô" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Judô') ? 'selected' : '' ?>>Judô
                    </option>
                    <option value="Karatê" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Karatê') ? 'selected' : '' ?>>Karatê
                    </option>
                    <option value="Kung Fu" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Kung Fu') ? 'selected' : '' ?>>Kung Fu
                    </option>
                    <option value="MMA" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'MMA') ? 'selected' : '' ?>>MMA
                    </option>
                    <option value="Muay Thai" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Muay Thai') ? 'selected' : '' ?>>Muay Thai
                    </option>
                    <option value="Taekwondo" <?= (isset($Atleta->esporte) && $Atleta->esporte == 'Taekwondo') ? 'selected' : '' ?>>Taekwondo
                    </option>
                </select>
            </div>
            
            <div class="col-md-4">
            <label for="peso" class="form-label">Peso</label>
                <input type="peso" name="peso" id="peso" placeholder="Digite o peso do Atleta. Ex.:102.65" required
                    class="form-control" value="<?php echo $Atleta->peso ?? null; ?>">
            </div>

            <div class="col-md-4">
            <label for="categoria" class="form-label">Categoria</label>
                <input type="categoria" name="categoria" id="categoria" placeholder="Digite a categoria do Atleta" required
                    class="form-control" value="<?php echo $Atleta->categoria ?? null; ?>">
            </div>

            <div class="col-md-4">
                <label for="fk_id_academia" class="form-label">Academia</label>
                <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                    <option disabled <?= (!isset($Atleta->fk_id_academia)) ? 'selected' : '' ?>>Selecione a Academia
                    </option>
                    <?php foreach ($academia as $ac): ?>
                        <option value="<?= $ac->id_academia ?>" <?= (!empty($Atleta) && intval($Atleta->fk_id_academia ?? 0) === intval($ac->id_academia)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ac->nome_fantasia) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
</body>

</html>