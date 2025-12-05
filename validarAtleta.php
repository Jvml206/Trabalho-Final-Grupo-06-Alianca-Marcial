<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Atleta = new Atleta();

// Verifica token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo "<script>window.alert('Token inválido.');window.location.href='index.php';</script>";
    exit;
}

$token = $_GET['token'];

// Busca atleta pelo token
$sql = "SELECT a.*, u.email, u.foto 
        FROM atleta a
        INNER JOIN usuario u ON u.id_usuario = a.fk_id_usuario
        WHERE a.token_validacao = :token
        LIMIT 1";

$stmt = $Atleta->getDb()->prepare($sql);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "<script>window.alert('Token inválido ou atleta já validado.');window.location.href='index.php';</script>";
    exit;
}

$atleta = $stmt->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Validação de Atleta</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>

    <main class="container">
        <div class="card shadow p-4">
            <h1 class="tituloh1">Validação do Cadastro de Atleta</h1>
            <div class="row gap-4 mb-3">
                <div class="dadosPedido">

                    <p><b>Atleta:</b> <?= $atleta->nome_atleta ?></p>
                    <p><b>Email:</b> <?= $atleta->email ?></p>
                    <p><b>Data de Nascimento:</b> <?= date('d/m/Y', strtotime($atleta->data_nascimento)) ?></p>
                    <p><b>Esporte:</b> <?= $atleta->esporte ?></p>
                    <p><b>Categoria:</b> <?= $atleta->categoria ?></p>
                    <p><b>Peso:</b><?= $atleta->peso?></p>
                    <?php if (!empty($atleta->foto)): ?>
                        <div class="fotoPedido">
                            <p><b>Foto enviada:</b></p>
                            <img src="Images/usuario/<?= $atleta->foto ?>" class="foto-usuario-cadastro">
                        </div>
                    <?php endif; ?>
                    <hr>
                    <form action="processaValidacaoAtleta.php" method="POST">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <button type="submit" name="acao" value="aprovar" class="btn-padrao">
                            Validar Cadastro
                        </button>
                        <button type="button" class="btn-voltar" data-bs-toggle="collapse"
                            data-bs-target="#motivoReprovacao">
                            Invalidar Cadastro
                        </button>
                        <div id="motivoReprovacao" class="collapse mt-3">
                            <label><b>Motivo da Invalidação:</b></label>
                            <textarea name="motivo" class="form-control" minlength="5"></textarea>
                            <button type="submit" name="acao" value="reprovar" class="btn-voltar mt-3">
                                Enviar Invalidação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
