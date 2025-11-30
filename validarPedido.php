<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$PedidoAjuda = new PedidoAjuda();

// Verifica token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo "<script>window.alert('Token inválido.');window.location.href='index.php';</script>";
    exit;
}

$token = $_GET['token'];

// Busca pedido pelo token
$sql = "SELECT pa.*, a.nome_atleta AS nome_atleta
        FROM pedido_ajuda pa
        INNER JOIN atleta a ON a.id_atleta = pa.fk_id_atleta
        WHERE token_validacao = :token LIMIT 1";

$stmt = $PedidoAjuda->getDb()->prepare($sql);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "<script>window.alert('Token inválido ou pedido já validado.');window.location.href='index.php';</script>";
    exit;
}

$pedido = $stmt->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Validação do Pedido de Ajuda</title>
</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="card shadow p-4">

            <h1 class="tituloh1">Validação do Pedido de Ajuda</h1>

            <p><b>Aluno:</b> <?= $pedido->nome_atleta ?></p>
            <p><b>Título:</b> <?= $pedido->titulo ?></p>
            <p><b>Descrição:</b> <?= nl2br($pedido->descricao) ?></p>
            <p><b>Valor Necessário:</b> R$ <?= number_format($pedido->valor_necessario, 2, ',', '.') ?></p>

            <?php if (!empty($pedido->imagem)): ?>
                <p><b>Imagem enviada:</b></p>
                <img src="Images/pedidoDeAjuda/<?= $pedido->imagem ?>" style="max-width: 300px;" class="img-thumbnail">
            <?php endif; ?>

            <hr>

            <form action="processaValidacaoPedido.php" method="POST">

                <input type="hidden" name="token" value="<?= $token ?>">

                <button type="submit" name="acao" value="aprovar" class="btn btn-success">Aprovar Pedido</button>

                <button type="button" class="btn btn-danger" data-bs-toggle="collapse"
                    data-bs-target="#motivoReprovacao">Reprovar Pedido</button>

                <div id="motivoReprovacao" class="collapse mt-3">
                    <label><b>Motivo da Reprovação:</b></label>
                    <textarea name="motivo" class="form-control" minlength="5"></textarea>
                    <button type="submit" name="acao" value="reprovar" class="btn btn-danger mt-3">Enviar
                        Reprovação</button>
                </div>

            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>