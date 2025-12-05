<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PedidoAjuda extends CRUD
{
    protected $table = "pedido_ajuda";
    private $id_pedido_ajuda;
    private $titulo;
    private $descricao;
    private $valor_necessario;
    private $valor_atingido;
    private $pix;
    private $imagem;
    private $status_validacao;
    private $token_validacao;
    private $meta;
    private $fk_id_atleta;

    public function getIdPedidoAjuda()
    {
        return $this->id_pedido_ajuda;
    }
    public function setIdPedidoAjuda($id_pedido_ajuda)
    {
        $this->id_pedido_ajuda = $id_pedido_ajuda;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getValorNecessario()
    {
        return $this->valor_necessario;
    }
    public function setValorNecessario($valor_necessario)
    {
        $this->valor_necessario = $valor_necessario;
    }

    public function getValorAtingido()
    {
        return $this->valor_atingido;
    }
    public function setValorAtingido($valor_atingido)
    {
        $this->valor_atingido = $valor_atingido;
    }

    public function getPix()
    {
        return $this->pix;
    }
    public function setPix($pix)
    {
        $this->pix = $pix;
    }

    public function getImagem()
    {
        return $this->imagem;
    }
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    public function getFkIdAtleta()
    {
        return $this->fk_id_atleta;
    }
    public function setFkIdAtleta($fk_id_atleta)
    {
        $this->fk_id_atleta = $fk_id_atleta;
    }

    public function getStatusValidacao()
    {
        return $this->status_validacao;
    }
    public function setStatusValidacao($status_validacao)
    {
        $this->status_validacao = $status_validacao;
    }

    public function getTokenValidacao()
    {
        return $this->token_validacao;
    }
    public function setTokenValidacao($token_validacao)
    {
        $this->token_validacao = $token_validacao;
    }

    public function getMeta()
    {
        return $this->meta;
    }
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (titulo, descricao, valor_necessario, valor_atingido, pix, imagem, fk_id_atleta) 
                VALUES (:titulo, :descricao, :valor_necessario, :valor_atingido, :pix, :imagem, :fk_id_atleta)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':valor_necessario', $this->valor_necessario);
        $stmt->bindParam(':valor_atingido', $this->valor_atingido);
        $stmt->bindParam(':pix', $this->pix);
        $stmt->bindParam(':imagem', $this->imagem);
        $stmt->bindParam(':fk_id_atleta', $this->fk_id_atleta);

        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // ← retorna o ID do pedido criado
        }

        return false;
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET titulo = :titulo, 
                    descricao = :descricao, 
                    valor_necessario = :valor_necessario, 
                    valor_atingido = :valor_atingido, 
                    pix = :pix, 
                    imagem = :imagem,
                    status_validacao = :status_validacao,
                    fk_id_atleta = :fk_id_atleta
                WHERE $campo = :id_pedido_ajuda";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':valor_necessario', $this->valor_necessario);
        $stmt->bindParam(':valor_atingido', $this->valor_atingido);
        $stmt->bindParam(':pix', $this->pix);
        $stmt->bindParam(':imagem', $this->imagem);
        $stmt->bindParam(':status_validacao', $this->status_validacao);
        $stmt->bindParam(':fk_id_atleta', $this->fk_id_atleta);
        $stmt->bindParam(":id_pedido_ajuda", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function searchAll($usuario, $id)
    {
        $sql = "SELECT * FROM $this->table WHERE $usuario = :fk_id_atleta";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":fk_id_atleta", $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function statusMeta(int $id)
    {
        $sql = "UPDATE $this->table SET meta = 'atingida', valor_atingido = valor_necessario WHERE id_pedido_ajuda = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function metaAtingida(int $id)
    {
        $sql = "UPDATE $this->table SET meta = 'atingida' WHERE id_pedido_ajuda = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function validacaoPedido($idAtleta, $mensagem, $assunto, $idPedido)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';

        try {
            // Buscar o e-mail do instrutor via atleta
            $sql = "SELECT i.email AS emailInstrutor
                FROM atleta a
                INNER JOIN instrutor i ON i.id_instrutor = a.fk_id_instrutor
                WHERE a.id_atleta = :idAtleta";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idAtleta', $idAtleta, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return false; // atleta não encontrado ou sem instrutor
            }

            $dados = $stmt->fetch(PDO::FETCH_OBJ);
            $emailInstrutor = $dados->emailInstrutor;
            
            // Gerar token único
            $token = bin2hex(random_bytes(32));
            $expiraValidacao = date('Y-m-d H:i:s', strtotime('+72 hours'));

            $sql = "UPDATE pedido_ajuda SET token_validacao = :token, expira_validacao = :expira_validacao
                WHERE id_pedido_ajuda = :idPedido";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':expira_validacao', $expiraValidacao, PDO::PARAM_STR);
            $stmt->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
            $stmt->execute();

            // Montar link
            $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $dominio = $_SERVER['HTTP_HOST'];
            $caminho = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

            $linkValidacao = "$protocolo://$dominio$caminho/validarPedido.php?token=$token";

            // Configurar PHPMailer
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            try {
                $config = parse_ini_file(__DIR__ . '/../config.ini', true)['email'];

                $mail->isSMTP();
                $mail->Host = $config['Host'];
                $mail->SMTPAuth = $config['SMTPAuth'];
                $mail->Username = $config['Username'];
                $mail->Password = $config['Password'];
                $mail->SMTPSecure = $config['SMTPSecure'];
                $mail->Port = $config['Port'];

                // remetente e destinatário
                $mail->setFrom($config['Username'], 'Cooperativa Aliança Marcial');
                $mail->addAddress($emailInstrutor);

                $sqlNome = "SELECT nome_atleta FROM atleta WHERE id_atleta = :idAtleta";
                $stmt = $this->db->prepare($sqlNome);
                $stmt->bindParam(':idAtleta', $idAtleta, PDO::PARAM_INT);
                $stmt->execute();

                $nomeAtleta = $stmt->fetch(PDO::FETCH_ASSOC)['nome_atleta'];

                // conteúdo
                $mail->isHTML(true);
                $mail->Subject = $assunto;
                $mail->Body = "
                <p>Olá, Instrutor(a).</p>
                <p>O(A) atleta $nomeAtleta enviou um pedido de ajuda que precisa ser validado.</p>
                <p>$mensagem</p>
                <p><a href='$linkValidacao'>Clique aqui para validar ou reprovar</a></p>
            ";

                $mail->AltBody = "Acesse o link para validar o pedido: $linkValidacao";

                $mail->send();
                return true;

            } catch (Exception $e) {
                error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
                return false;
            }

        } catch (PDOException $e) {
            error_log("Erro em validacaoPedido: " . $e->getMessage());
            return false;
        }
    }

    public function enviarEmailAtletaPedido($idAtleta, $assunto, $mensagem)
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

            $mail->setFrom($config['Username'], 'Cooperativa Aliança Marcial');
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

    public function allIndex()
    {
        $sql = "SELECT * FROM $this->table WHERE status_validacao = 'aprovado' AND meta = 'pendente' LIMIT 12";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function allPedAju()
    {
        $sql = "SELECT * FROM $this->table WHERE status_validacao = 'aprovado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
