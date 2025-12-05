<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Instrutor = new Instrutor();

// Verifica token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo "<script>window.alert('Token inválido.');window.location.href='index.php';</script>";
    exit;
}

$token = $_GET['token'];

// Busca instrutor pelo token
$sql = "SELECT i.*, u.email, u.foto 
        FROM instrutor i
        INNER JOIN usuario u ON u.id_usuario = i.fk_id_usuario
        WHERE i.token_validacao = :token
        LIMIT 1";

$stmt = $Instrutor->getDb()->prepare($sql);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "<script>window.alert('Token inválido ou instrutor já validado.');window.location.href='index.php';</script>";
    exit;
}

$instrutor = $stmt->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Validação de Instrutor</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>

    <main class="container">
        <div class="card shadow p-4">

            <h1 class="tituloh1">Validação do Cadastro de Instrutor</h1>
            <div class="row gap-4 mb-3">
                <div class="dadosPedido">

                    <p><b>Nome:</b> <?= $instrutor->nome_instrutor ?></p>
                    <p><b>Email:</b> <?= $instrutor->email ?></p>
                    <p><b>Telefone:</b> <?= $instrutor->telefone ?></p>

                    <?php if (!empty($instrutor->foto)): ?>
                        <div class="fotoPedido">
                            <p><b>Foto enviada:</b></p>
                            <img src="Images/usuario/<?= $instrutor->foto ?>" class="foto-usuario-cadastro">
                        </div>
                    <?php endif; ?>
                    <hr>
                    <form action="processaValidacaoInstrutor.php" method="POST">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <button type="submit" name="acao" value="aprovar" class="btn-padrao">Validar Cadastro</button>
                        <button type="button" class="btn-voltar" data-bs-toggle="collapse"data-bs-target="#motivoReprovacao">
                            Invalidar Cadastro
                        </button>

                        <div id="motivoReprovacao" class="collapse mt-3">
                            <label><b>Motivo da Invalidação:</b></label>
                            <textarea name="motivo" class="form-control" minlength="5"></textarea>
                            <button type="submit" name="acao" value="reprovar" class="btn-voltar mt-3">Enviar Invalidação</button>
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