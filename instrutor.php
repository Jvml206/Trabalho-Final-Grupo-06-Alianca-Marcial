<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Instrutor = new Instrutor();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Ameaca->setNomePopularAmeaca(filter_input(INPUT_POST, "nome_popular", FILTER_SANITIZE_STRING));
    $Ameaca->setNomeCientificoAmeaca(filter_input(INPUT_POST, "nome_cientifico", FILTER_SANITIZE_STRING));
    $Ameaca->setDescricaoAmeaca(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
    $Ameaca->setTipoAmeaca(filter_input(INPUT_POST, "tipo", FILTER_SANITIZE_STRING));
    $Ameaca->setReferenciasAmeaca(filter_input(INPUT_POST, "referencias", FILTER_SANITIZE_STRING));
    $Ameaca->setIdCulturaFK(filter_input(INPUT_POST, "idCulturaFK", FILTER_SANITIZE_NUMBER_INT));
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)):
        //Tenta adicionar e exibe a mensagemao usuário
        if ($Ameaca->add()) {
            echo "<script>window.alert('Cadastro de praga/doença realizado com sucesso.');window.location.href=ameaca.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a praga/doença.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Ameaca->update('idAmeaca', $id)) {
            echo "<script>window.alert('Praga/doença alterada com sucesso.'); 
            window.location.href='listaAmeaca.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar a Praga/doença.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    if ($Ameaca->delete("idAmeaca", $id)) {
        header("location:listaAmeaca.php");
    } else {
        echo "<script>window.alert('Erro ao excluir'); window(document.referrer, '_self');</script>";
    }

endif;
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/layout.css">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/favicon-32x32F.png">
    <title>Cadastro de Praga/Doença</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>
    <main class="container">

        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        $Cultura = new Cultura;
        $cultura = $Cultura->all();
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtAmeacas = new Ameaca;
            $id = intval(filter_input(INPUT_POST, "id"));
            $Ameacas = $edtAmeacas->search("idAmeaca", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Praga/Doença</h2>

        <form action="ameaca.php" method="post" class="row g3 mt-3">

            <input type="hidden" value="<?php echo $Ameacas->idAmeaca ?? null; ?>" name="id">

            <div class="col-md-6 mb-3">
                <label for="nome_popular" class="form-label">Nome Popular</label>
                <input type="text" name="nome_popular" id="nome_popular"
                    placeholder="Digite o Nome popular da Praga/Doença" required class="form-control"
                    value="<?php echo $Ameacas->nomePopularAmeaca ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="nome_cientifico" class="form-label">Nome Cientifico</label>
                <input type="text" name="nome_cientifico" id="nome_cientifico"
                    placeholder="Digite Nome cientifico da Praga/Doença" required class="form-control"
                    value="<?php echo $Ameacas->nomeCientificoAmeaca ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao" id="descricao" rows="3"
                    required> <?php echo $Ameacas->descricaoAmeaca ?? null; ?></textarea>
            </div>

            <div class="col-md-6">
                <label for="referencias" class="form-label">Referências</label>
                <textarea class="form-control" name="referencias" id="referencias" rows="3"
                    required> <?php echo $Ameacas->referenciasAmeaca ?? null; ?></textarea>
            </div>

            <div class="col-6">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" class="form-select" aria-label="Default select example" id="tipo" required>
                    <option disabled <?= (!isset($Ameacas->tipoAmeaca)) ? 'selected' : '' ?>>Selecione o Tipo</option>
                    <option value="Praga" <?= (isset($Ameacas->tipoAmeaca) && $Ameacas->tipoAmeaca == 'Praga') ? 'selected' : '' ?>> Praga</option>
                    <option value="Doença" <?= (isset($Ameacas->tipoAmeaca) && $Ameacas->tipoAmeaca == 'Doença') ? 'selected' : '' ?>> Doença</option>
                </select>
            </div>

            <div class="col-6">
                <label for="cultura" class="form-label">Cultura</label>
                <select name="idCulturaFK" class="form-select" id="cultura" required>
                    <option disabled <?= (!isset($Ameacas->idCulturaFK)) ? 'selected' : '' ?>>Selecione a Cultura
                    </option>
                    <?php foreach ($cultura as $c): ?>
                        <option value="<?= $c->idCultura ?>" <?= (!empty($Ameacas) && intval($Ameacas->idCulturaFK) === intval($c->idCultura)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c->nomeCultura) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 mt-3 mb-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar"
                    class="btn btn-outline-success">Gravar</button>

                <a href="listaAmeaca.php" role="button" class="btn btn-outline-danger">Cancelar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>