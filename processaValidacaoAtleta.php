<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Atleta = new Atleta();

if (!isset($_POST['token'], $_POST['acao'])) {
    echo "<script>alert('Dados inválidos!');window.location.href='index.php';</script>";
    exit;
}

$token = $_POST['token'];
$acao = $_POST['acao'];

$sql = "SELECT * FROM atleta WHERE token_validacao = :token LIMIT 1";
$stmt = $Atleta->getDb()->prepare($sql);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "<script>alert('Token inválido!');window.location.href='index.php';</script>";
    exit;
}

$atleta = $stmt->fetch(PDO::FETCH_OBJ);

if ($acao == "aprovar") {

    $sql = "UPDATE atleta 
        SET status_validacao = 'valido', token_validacao = NULL, expira_validacao = NULL
        WHERE id_atleta = :id";

    $stmt = $Atleta->getDb()->prepare($sql);
    $stmt->bindParam(":id", $atleta->id_atleta);
    $stmt->execute();

    $assunto = "Seu pedido de criação de conta foi validado";
    $mensagem = "Parabéns! Seu pedido de criação de conta foi validado com sucesso";
    $Atleta->enviarEmailAtletaConta(
        $atleta->id_atleta,
        $assunto,
        $mensagem
    );

    echo "<script>alert('Atleta validado com sucesso!');window.location.href='index.php';</script>";
    exit;

} else if ($acao == "reprovar") {

    $motivo = $_POST['motivo'] ?? '';

    $sql = "UPDATE atleta 
        SET status_validacao = 'invalido', motivo_reprovacao = :motivo, token_validacao = NULL, expira_validacao = NULL
        WHERE id_atleta = :id";

    $stmt = $Atleta->getDb()->prepare($sql);
    $stmt->bindParam(":motivo", $motivo);
    $stmt->bindParam(":id", $atleta->id_atleta);
    $stmt->execute();

    $assunto = "Seu pedido de criação de conta foi invalidado";
    $mensagem = "
        Infelizmente seu pedido foi invalidado.<br><br>
        <b>Motivo:</b> $motivo
    ";
    $Atleta->enviarEmailAtletaConta(
        $atleta->id_atleta,
        $assunto,
        $mensagem
    );

    echo "<script>alert('Atleta invalidado. Motivo: $motivo');window.location.href='index.php';</script>";
    exit;
}
echo "Ação inválida.";