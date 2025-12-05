<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Atleta extends CRUD
{
    protected $table = "atleta";
    private $id_atleta;
    private $nome_atleta;
    private $data_nascimento;
    private $biografia;
    private $sexo;
    private $esporte;
    private $peso;
    private $categoria;
    private $status_validacao;
    private $fk_id_usuario;
    private $fk_id_academia;
    private $fk_id_instrutor;

    public function getIdAtleta()
    {
        return $this->id_atleta;
    }
    public function setIdAtleta($id_atleta)
    {
        $this->id_atleta = $id_atleta;
    }

    public function getNomeAtleta()
    {
        return $this->nome_atleta;
    }
    public function setNomeAtleta($nome_atleta)
    {
        $this->nome_atleta = $nome_atleta;
    }

    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }
    public function setDataNascimento($data_nascimento)
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getBiografia()
    {
        return $this->biografia;
    }
    public function setBiografia($biografia)
    {
        $this->biografia = $biografia;
    }

    public function getSexo()
    {
        return $this->sexo;
    }
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    public function getEsporte()
    {
        return $this->esporte;
    }
    public function setEsporte($esporte)
    {
        $this->esporte = $esporte;
    }

    public function getPeso()
    {
        return $this->peso;
    }
    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }
    public function getStatusValidacao()
    {
        return $this->status_validacao;
    }
    public function setStatusValidacao($status_validacao)
    {
        $this->status_validacao = $status_validacao;
    }

    public function getFkIdUsuario()
    {
        return $this->fk_id_usuario;
    }
    public function setFkIdUsuario($fk_id_usuario)
    {
        $this->fk_id_usuario = $fk_id_usuario;
    }
    public function getFkIdAcademia()
    {
        return $this->fk_id_academia;
    }
    public function setFkIdAcademia($fk_id_academia)
    {
        $this->fk_id_academia = $fk_id_academia;
    }
    public function getFkIdInstrutor()
    {
        return $this->fk_id_instrutor;
    }
    public function setFkIdInstrutor($fk_id_instrutor)
    {
        $this->fk_id_instrutor = $fk_id_instrutor;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (nome_atleta, data_nascimento, biografia, sexo, peso, esporte, categoria, fk_id_academia, fk_id_usuario, fk_id_instrutor) 
                VALUES (:nome_atleta, :data_nascimento, :biografia, :sexo, :peso, :esporte, :categoria, :fk_id_academia, :fk_id_usuario, :fk_id_instrutor)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_atleta', $this->nome_atleta);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':biografia', $this->biografia);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':peso', $this->peso);
        $stmt->bindParam(':esporte', $this->esporte);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':fk_id_academia', $this->fk_id_academia);
        $stmt->bindParam(':fk_id_usuario', $this->fk_id_usuario);
        $stmt->bindParam(':fk_id_instrutor', $this->fk_id_instrutor);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_atleta = :nome_atleta, 
                    data_nascimento = :data_nascimento, 
                    biografia = :biografia, 
                    sexo = :sexo, 
                    peso = :peso, 
                    esporte = :esporte, 
                    categoria = :categoria, 
                    status_validacao = :status_validacao, 
                    fk_id_academia = :fk_id_academia,
                    fk_id_usuario = :fk_id_usuario,
                    fk_id_instrutor = :fk_id_instrutor
                WHERE $campo = :id_atleta";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_atleta', $this->nome_atleta);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':biografia', $this->biografia);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':peso', $this->peso);
        $stmt->bindParam(':esporte', $this->esporte);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':status_validacao', $this->status_validacao);
        $stmt->bindParam(':fk_id_academia', $this->fk_id_academia);
        $stmt->bindParam(':fk_id_usuario', $this->fk_id_usuario);
        $stmt->bindParam(':fk_id_instrutor', $this->fk_id_instrutor);
        $stmt->bindParam(":id_atleta", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function verificarPorUsuario($idUsuario)
    {
        $sql = "SELECT COUNT(*) AS total FROM atleta WHERE fk_id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;
    }

    public function enviarValidacaoInstrutor($idAtleta, $mensagem, $assunto)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';

        try {

            // Buscar o e-mail do instrutor
            $sql = "SELECT 
                    i.email AS emailInstrutor,
                    i.nome_instrutor,
                    a.nome_atleta
                FROM atleta a
                INNER JOIN instrutor i ON i.id_instrutor = a.fk_id_instrutor
                WHERE a.id_atleta = :idAtleta";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idAtleta', $idAtleta, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false;
            }

            $dados = $stmt->fetch(PDO::FETCH_OBJ);
            $emailInstrutor = $dados->emailInstrutor;
            $nomeInstrutor = $dados->nome_instrutor;
            $nomeAtleta = $dados->nome_atleta;

            // Criar token único
            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+72 hours'));

            // Salvar token no atleta
            $sqlToken = "UPDATE atleta 
                     SET token_validacao = :token, expira_validacao = :expira
                     WHERE id_atleta = :idAtleta";

            $stmtToken = $this->db->prepare($sqlToken);
            $stmtToken->bindParam(':token', $token, PDO::PARAM_STR);
            $stmtToken->bindParam(':expira', $expira, PDO::PARAM_STR);
            $stmtToken->bindParam(':idAtleta', $idAtleta, PDO::PARAM_INT);
            $stmtToken->execute();


            // Criar link
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $dominio = $_SERVER['HTTP_HOST'];
            $caminho = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

            $linkValidacao = "$protocolo://$dominio$caminho/validarAtleta.php?token=$token";


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
            $mail->addAddress($emailInstrutor, $nomeInstrutor);

            $mail->isHTML(true);
            $mail->Subject = $assunto;

            $mail->Body = "
            <p>Olá, Instrutor(a) <strong>$nomeInstrutor</strong>.</p>
            <p>O(A) atleta <strong>$nomeAtleta</strong> se cadastrou em sua academia.</p>
            <p>$mensagem</p>
            <p><a href='$linkValidacao'>Clique aqui para validar ou invalidar o atleta</a></p>";

            $mail->AltBody = "Valide o cadastro do atleta acessando: $linkValidacao";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erro ao enviar email de validação: {$e->getMessage()}");
            return false;
        }
    }

    public function enviarEmailAtletaConta($idAtleta, $assunto, $mensagem)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';

        try {
            $sql = "SELECT a.nome_atleta AS nome_atleta, u.email AS email
                FROM atleta a
                INNER JOIN usuario u ON u.id_usuario = a.fk_id_usuario
                WHERE a.id_atleta = :idAtleta";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idAtleta', $idAtleta, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false; // atleta não encontrado
            }

            $dados = $stmt->fetch(PDO::FETCH_OBJ);
            $nome_atleta = $dados->nome_atleta;
            $email = $dados->email;

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

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

            // Corpo do email
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = "
            <p>Olá, <b>$nome_atleta</b>.</p>
            <p>$mensagem</p>
            <p>Atenciosamente,<br>Equipe Aliança Marcial</p>
        ";

            $mail->AltBody = strip_tags($mensagem);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail para atleta: {$mail->ErrorInfo}");
            return false;
        }
    }

}
