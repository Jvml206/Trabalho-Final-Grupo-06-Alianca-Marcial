<?php
spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Instrutor = new Instrutor();

if (!isset($_POST['token'], $_POST['acao'])) {
    echo "<script>alert('Dados inválidos!');window.location.href='index.php';</script>";
    exit;
}

$token = $_POST['token'];
$acao = $_POST['acao'];

// Buscar instrutor pelo token
$sql = "SELECT * FROM instrutor WHERE token_validacao = :token LIMIT 1";
$stmt = $Instrutor->getDb()->prepare($sql);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "<script>alert('Token inválido!');window.location.href='index.php';</script>";
    exit;
}

$instrutor = $stmt->fetch(PDO::FETCH_OBJ);

if ($acao == "aprovar") {

    // Aprova o instrutor
    $sql = "UPDATE instrutor 
        SET status_validacao = 'valido', token_validacao = NULL, expira_validacao = NULL
        WHERE id_instrutor = :id";

    $stmt = $Instrutor->getDb()->prepare($sql);
    $stmt->bindParam(":id", $instrutor->id_instrutor);
    $stmt->execute();

    // Enviar e-mail ao instrutor
    $assunto = "Sua conta de instrutor foi validada";
    $mensagem = "Parabéns! Sua conta foi validada com sucesso.";

    $Instrutor->enviarEmailInstrutorConta(
        $instrutor->id_instrutor,
        $assunto,
        $mensagem
    );

    echo "<script>alert('Instrutor validado com sucesso!');window.location.href='index.php';</script>";
    exit;

} else if ($acao == "reprovar") {

    $motivo = $_POST['motivo'] ?? '';

    $sql = "UPDATE instrutor 
        SET status_validacao = 'invalido', motivo_reprovacao = :motivo, token_validacao = NULL, expira_validacao = NULL
        WHERE id_instrutor = :id";

    $stmt = $Instrutor->getDb()->prepare($sql);
    $stmt->bindParam(":motivo", $motivo);
    $stmt->bindParam(":id", $instrutor->id_instrutor);
    $stmt->execute();

    // Enviar e-mail ao instrutor
    $assunto = "Sua conta de instrutor foi invalidada";
    $mensagem = "
        Infelizmente sua conta foi invalidada.<br><br>
        <b>Motivo:</b> $motivo
    ";

    $Instrutor->enviarEmailInstrutorConta(
        $instrutor->id_instrutor,
        $assunto,
        $mensagem
    );

    echo "<script>alert('Instrutor invalidado. Motivo: $motivo');window.location.href='index.php';</script>";
    exit;
}
echo "Ação inválida.";