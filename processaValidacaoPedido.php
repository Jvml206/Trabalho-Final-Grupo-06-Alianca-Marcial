<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$PedidoAjuda = new PedidoAjuda();
$dadoPedido = $PedidoAjuda->searchStr("token_validacao", $_POST['token']);

if (!isset($_POST['token']) || empty($_POST['token']) || strtotime($dadoPedido->expira_validacao) < time()) {
    die("Token inválido.");
}

$token = $_POST['token'];
$acao = $_POST['acao'];

// Busca pedido pelo token
$sql = "SELECT * FROM pedido_ajuda WHERE token_validacao = :token LIMIT 1";
$stmt = $PedidoAjuda->getDb()->prepare($sql);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    die("Token inválido ou pedido já validado.");
}

$pedido = $stmt->fetch(PDO::FETCH_OBJ);

if ($acao === "aprovar") {

    $sql = "UPDATE pedido_ajuda 
            SET status_validacao = 'aprovado', token_validacao = NULL, expira_validacao = NULL
            WHERE id_pedido_ajuda = :id";

    $stmt = $PedidoAjuda->getDb()->prepare($sql);
    $stmt->bindParam(":id", $pedido->id_pedido_ajuda, PDO::PARAM_INT);
    $stmt->execute();

    $assunto = "Seu pedido de ajuda foi aprovado!";
    $mensagem = "Parabéns! Seu pedido de ajuda foi aprovado com sucesso.";

    $PedidoAjuda->enviarEmailAtletaPedido(
        $pedido->fk_id_atleta,
        $assunto,
        $mensagem
    );

    echo "<script>window.alert('Pedido aprovado com sucesso!');window.location.href='index.php';</script>";
    exit;

} elseif ($acao === "reprovar") {

    if (empty($_POST['motivo'])) {
        die("Motivo obrigatório para reprovar.");
    }

    $motivo = $_POST['motivo'];

    $sql = "UPDATE pedido_ajuda 
            SET status_validacao = 'reprovado', motivo_reprovacao = :motivo, token_validacao = NULL, expira_validacao = NULL
            WHERE id_pedido_ajuda = :id";

    $stmt = $PedidoAjuda->getDb()->prepare($sql);
    $stmt->bindParam(":motivo", $motivo);
    $stmt->bindParam(":id", $pedido->id_pedido_ajuda, PDO::PARAM_INT);
    $stmt->execute();

    $assunto = "Seu pedido de ajuda foi reprovado";
    $mensagem = "
        Infelizmente seu pedido foi reprovado.<br><br>
        <b>Motivo:</b> $motivo
    ";

    $PedidoAjuda->enviarEmailAtletaPedido(
        $pedido->fk_id_atleta,
        $assunto,
        $mensagem
    );

    echo "<script>window.alert('Pedido reprovado. Motivo: $motivo');window.location.href='index.php';</script>";
    exit;
}

echo "Ação inválida.";