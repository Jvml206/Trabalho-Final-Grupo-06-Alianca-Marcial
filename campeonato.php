<?php
$nivelPermitido = ['Administrador'];
require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Campeonato = new Campeonato();

if (filter_has_var(INPUT_POST, "btnCadastrar")):
    $Campeonato->setNomeCampeonato(filter_input(INPUT_POST, "nome_campeonato", FILTER_SANITIZE_STRING));
    $Campeonato->setDataInicio(filter_input(INPUT_POST, "data_inicio", FILTER_SANITIZE_STRING));
    $Campeonato->setDataFim(filter_input(INPUT_POST, "data_fim", FILTER_SANITIZE_STRING));
    $Campeonato->setLocal(filter_input(INPUT_POST, "local", FILTER_SANITIZE_STRING));
    $Campeonato->setPais(filter_input(INPUT_POST, "pais", FILTER_SANITIZE_STRING));
    $Campeonato->setCidade(filter_input(INPUT_POST, "cidade", FILTER_SANITIZE_STRING));
    $Campeonato->setEsporte(filter_input(INPUT_POST, "esporte", FILTER_SANITIZE_STRING));
    $id = filter_input(INPUT_POST, 'id_campeonato');
    if (empty($id)):
        //Tenta adicionar e exibe a mensagem ao usuário
        if ($Campeonato->add()) {
            echo "<script>window.alert('Cadastro de campeonato realizado com sucesso.');window.location.href=campeonato.php;</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar a campeonato.');window.open(document.referrer,'_self');</script>";
        }
    else:
        if ($Campeonato->update('id_campeonato', $id)) {
            echo "<script>window.alert('Campeonato alterada com sucesso.'); 
            window.location.href='listaCampeonato.php';</script>";
        } else {
            echo "<script> window.alert('Erro ao alterar a Campeonato.');
            window.open(document.referrer, '_self'); </script>";
        }
    endif;
elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id = intval(filter_input(INPUT_POST, "id"));
    if ($Campeonato->delete("id_campeonato", $id)) {
        header("location:listaCampeonato.php");
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
    <link rel="stylesheet" href="CSS/baseAdmin.css">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Campeonato</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>
    
    <main class="container">
        <?php
        spl_autoload_register(function ($class) {
            require_once "Classes/{$class}.class.php";
        });

        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $edtCampeonato = new Campeonato();
            $id = intval(filter_input(INPUT_POST, "id"));
            $Campeonato = $edtCampeonato->search("id_campeonato", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Campeonato</h2>

        <form action="campeonato.php" method="post" class="row g3 mt-3">

            <input type="hidden" value="<?php echo $Campeonato->id_campeonato ?? null; ?>" name="id_campeonato">

            <div class="col-md-6">
                <label for="nome_campeonato" class="form-label">Nome do Campeonato</label>
                <input type="text" name="nome_campeonato" id="nome_campeonato"
                    placeholder="Digite o nome campeonato" required class="form-control"
                    value="<?php echo $Campeonato->nome_campeonato ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="data_inicio" class="form-label">Data de Início</label>
                <input type="date" name="data_inicio" id="data_inicio"
                    required class="form-control" value="<?php echo $Campeonato->data_inicio ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="data_fim" class="form-label">Data de Fim</label>
                <input type="date" name="data_fim" id="data_fim" required
                    class="form-control" value="<?php echo $Campeonato->data_fim ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="local" class="form-label">Local</label>
                <input type="text" name="local" id="local" placeholder="Digite o local do campeonato" required
                class="form-control" value="<?php echo $Campeonato->local ?? null; ?>">
            </div>
            
            <div class="col-md-6">
                <label for="pais" class="form-label">País</label>
                <input type="text" name="pais" id="pais" placeholder="Digite o país do campeonato" required
                    class="form-control" value="<?php echo $Campeonato->pais ?? null; ?>">
            </div>
            
            <div class="col-md-6">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" name="cidade" id="cidade" placeholder="Digite a cidade do campeonato" required
                    class="form-control" value="<?php echo $Campeonato->cidade ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="esporte" class="form-label">Esporte</label>
                <select id="esporte" name="esporte" class="form-select" aria-label="Default select example">
                    <option disabled <?= (!isset($Campeonato->esporte)) ? 'selected' : '' ?>>Selecione o Esporte</option>
                    <option value="Aikidô" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Aikidô') ? 'selected' : '' ?>>Aikidô
                    </option>
                    <option value="Boxe" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Boxe') ? 'selected' : '' ?>>Boxe
                    </option>
                    <option value="Capoeira" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Capoeira') ? 'selected' : '' ?>>Capoeira
                    </option>
                    <option value="Jiu-Jitsu Brasileiro" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Jiu-Jitsu Brasileiro') ? 'selected' : '' ?>>Jiu-Jitsu Brasileiro
                    </option>
                    <option value="Judô" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Judô') ? 'selected' : '' ?>>Judô
                    </option>
                    <option value="Karatê" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Karatê') ? 'selected' : '' ?>>Karatê
                    </option>
                    <option value="Kung Fu" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Kung Fu') ? 'selected' : '' ?>>Kung Fu
                    </option>
                    <option value="MMA" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'MMA') ? 'selected' : '' ?>>MMA
                    </option>
                    <option value="Muay Thai" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Muay Thai') ? 'selected' : '' ?>>Muay Thai
                    </option>
                    <option value="Taekwondo" <?= (isset($Campeonato->esporte) && $Campeonato->esporte == 'Taekwondo') ? 'selected' : '' ?>>Taekwondo
                    </option>
                </select>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                <a href="listaCampeonato.php" class="btn btn-outline-danger">Voltar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/dataCampeonato.js"></script>
</body>

</html>