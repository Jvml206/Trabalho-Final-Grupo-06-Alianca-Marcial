<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Instrutor extends CRUD
{
    protected $table = "instrutor";
    private $id_instrutor;
    private $nome_instrutor;
    private $data_nascimento;
    private $telefone;
    private $email;
    private $status_validacao;
    private $fk_id_academia;
    private $fk_id_usuario;

    public function getIdInstrutor()
    {
        return $this->id_instrutor;
    }
    public function setIdInstrutor($id_instrutor)
    {
        $this->id_instrutor = $id_instrutor;
    }

    public function getNomeInstrutor()
    {
        return $this->nome_instrutor;
    }
    public function setNomeInstrutor($nome_instrutor)
    {
        $this->nome_instrutor = $nome_instrutor;
    }

    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }
    public function setDataNascimento($data_nascimento)
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getStatusValidacao()
    {
        return $this->status_validacao;
    }
    public function setStatusValidacao($status_validacao)
    {
        $this->status_validacao = $status_validacao;
    }

    public function getFkIdAcademia()
    {
        return $this->fk_id_academia;
    }
    public function setFkIdAcademia($fk_id_academia)
    {
        $this->fk_id_academia = $fk_id_academia;
    }

    public function getFkIdUsuario()
    {
        return $this->fk_id_usuario;
    }
    public function setFkIdUsuario($fk_id_usuario)
    {
        $this->fk_id_usuario = $fk_id_usuario;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (nome_instrutor, data_nascimento, telefone, email, fk_id_academia, fk_id_usuario) 
                VALUES (:nome_instrutor, :data_nascimento, :telefone, :email, :fk_id_academia, :fk_id_usuario)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_instrutor', $this->nome_instrutor);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fk_id_academia', $this->fk_id_academia);
        $stmt->bindParam(':fk_id_usuario', $this->fk_id_usuario);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_instrutor = :nome_instrutor, 
                    data_nascimento = :data_nascimento,
                    telefone = :telefone, 
                    email = :email, 
                    status_validacao = :status_validacao,
                    fk_id_academia = :fk_id_academia,
                    fk_id_usuario = :fk_id_usuario
                WHERE $campo = :id_instrutor";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_instrutor', $this->nome_instrutor);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':status_validacao', $this->status_validacao);
        $stmt->bindParam(':fk_id_academia', $this->fk_id_academia, PDO::PARAM_INT);
        $stmt->bindParam(':fk_id_usuario', $this->fk_id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":id_instrutor", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function verificarPorUsuario($idUsuario)
    {
        $sql = "SELECT COUNT(*) AS total FROM instrutor WHERE fk_id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }

    public function enviarValidacaoAcademia($idInstrutor, $mensagem, $assunto)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';

        try {

            // Buscar o e-mail da academia responsável
            $sql = "SELECT 
                    ac.email AS emailAcademia,
                    ac.nome_fantasia,
                    i.nome_instrutor
                FROM instrutor i
                INNER JOIN academia ac ON ac.id_academia = i.fk_id_academia
                WHERE i.id_instrutor = :idInstrutor";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idInstrutor', $idInstrutor, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            $dados = $stmt->fetch(PDO::FETCH_OBJ);
            $emailAcademia = $dados->emailAcademia;
            $nomeAcademia = $dados->nome_fantasia;
            $nomeInstrutor = $dados->nome_instrutor;

            // Criar token único
            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+72 hours'));

            // Salvar token no instrutor
            $sqlToken = "UPDATE instrutor 
                     SET token_validacao = :token, expira_validacao = :expira
                     WHERE id_instrutor = :idInstrutor";

            $stmtToken = $this->db->prepare($sqlToken);
            $stmtToken->bindParam(':token', $token);
            $stmtToken->bindParam(':expira', $expira);
            $stmtToken->bindParam(':idInstrutor', $idInstrutor);
            $stmtToken->execute();


            // Criar link de validação
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $dominio = $_SERVER['HTTP_HOST'];
            $caminho = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

            $linkValidacao = "$protocolo://$dominio$caminho/validarInstrutor.php?token=$token";


            /* ENVIAR E-MAIL */
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            $config = parse_ini_file(__DIR__ . '/../config.ini', true)['email'];

            $mail->isSMTP();
            $mail->Host = $config['Host'];
            $mail->SMTPAuth = $config['SMTPAuth'];
            $mail->Username = $config['Username'];
            $mail->Password = $config['Password'];
            $mail->SMTPSecure = $config['SMTPSecure'];
            $mail->Port = $config['Port'];

            $mail->setFrom($config['Username'], 'Aliança Marcial');
            $mail->addAddress($emailAcademia, $nomeAcademia);

            $mail->isHTML(true);
            $mail->Subject = $assunto;

            $mail->Body = "
            <p>Olá, <strong>$nomeAcademia</strong>.</p>
            <p>O(A) instrutor(a) <strong>$nomeInstrutor</strong> realizou um cadastro e necessita de validação.</p>
            <p>$mensagem</p>
            <p><a href='$linkValidacao'>Clique aqui para validar ou reprovar o instrutor</a></p>";

            $mail->AltBody = strip_tags("Valide o instrutor: $linkValidacao");

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erro ao enviar email de validação de instrutor: {$e->getMessage()}");
            return false;
        }
    }
    public function enviarEmailInstrutorConta($idInstrutor, $assunto, $mensagem)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';

        try {

            $sql = "SELECT i.nome_instrutor, u.email
                FROM instrutor i
                INNER JOIN usuario u ON u.id_usuario = i.fk_id_usuario
                WHERE i.id_instrutor = :idInstrutor";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idInstrutor', $idInstrutor);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            $dados = $stmt->fetch(PDO::FETCH_OBJ);
            $nomeInstrutor = $dados->nome_instrutor;
            $email = $dados->email;

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            $config = parse_ini_file(__DIR__ . '/../config.ini', true)['email'];

            $mail->isSMTP();
            $mail->Host = $config['Host'];
            $mail->SMTPAuth = $config['SMTPAuth'];
            $mail->Username = $config['Username'];
            $mail->Password = $config['Password'];
            $mail->SMTPSecure = $config['SMTPSecure'];
            $mail->Port = $config['Port'];

            $mail->setFrom($config['Username'], 'Aliança Marcial');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $assunto;

            $mail->Body = "
            <p>Olá, <b>$nomeInstrutor</b>.</p>
            <p>$mensagem</p>
            <p>Atenciosamente,<br>Equipe Aliança Marcial</p>";

            $mail->AltBody = strip_tags($mensagem);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erro ao enviar email para instrutor: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function instrutoresValidos()
    {
        $sql = "SELECT * FROM instrutor WHERE status_validacao = 'valido'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
