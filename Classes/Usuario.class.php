<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Usuario extends CRUD
{
    protected $table = "usuario";
    private $id_usuario;
    private $nome_usuario;
    private $email;
    private $senha;
    private $tipo_usuario;
    private $foto;

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNomeUsuario()
    {
        return $this->nome_usuario;
    }
    public function setNomeUsuario($nome_usuario)
    {
        $this->nome_usuario = $nome_usuario;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSenha()
    {
        return $this->senha;
    }
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getTipoUsuario()
    {
        return $this->tipo_usuario;
    }
    public function setTipoUsuario($tipo_usuario)
    {
        $this->tipo_usuario = $tipo_usuario;
    }

    public function getFoto()
    {
        return $this->foto;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (nome_usuario, email, senha, tipo_usuario, foto) 
                VALUES (:nome_usuario, :email, :senha, :tipo_usuario, :foto)";

        $stmt = $this->db->prepare($sql);

        try {
            $stmt->bindParam(':nome_usuario', $this->nome_usuario);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->bindParam(':tipo_usuario', $this->tipo_usuario);
            $stmt->bindParam(':foto', $this->foto);

            if ($stmt->execute()) {
                // Armazena o ID do usuário recém-criado na propriedade da classe
                $this->id_usuario = $this->db->lastInsertId();
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
            return false;
        }
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_usuario = :nome_usuario, 
                    email = :email,
                    tipo_usuario = :tipo_usuario, 
                    foto = :foto
                WHERE $campo = :id_usuario";

        $stmt = $this->db->prepare($sql);

        try {
            $stmt->bindParam(':nome_usuario', $this->nome_usuario);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':tipo_usuario', $this->tipo_usuario);
            $stmt->bindParam(':foto', $this->foto);
            $stmt->bindParam(":id_usuario", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar usuário: " . $e->getMessage();
            return false;
        }
    }

    public function searchAtleta(string $campo, string $valor)
    {
        $sql = "SELECT * FROM $this->table WHERE $campo = :valor AND tipo_usuario = 'Atleta'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }
    public function usuariosAtletaDisponiveis()
    {
        $sql = "SELECT u.* FROM $this->table u WHERE u.tipo_usuario = 'Atleta' AND NOT EXISTS ( SELECT 1 FROM atleta a WHERE a.fk_id_usuario = u.id_usuario)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function searchInstrutor(string $campo, string $valor)
    {
        $sql = "SELECT * FROM $this->table WHERE $campo = :valor AND tipo_usuario = 'Instrutor'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_OBJ) : null;
    }
    public function usuariosInstrutorDisponiveis()
    {
        $sql = "SELECT u.* FROM $this->table u WHERE u.tipo_usuario = 'Instrutor' AND NOT EXISTS ( SELECT 1 FROM instrutor i WHERE i.fk_id_usuario = u.id_usuario)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function statusConta(int $id, string $status)
    {
        $sql = "UPDATE $this->table SET status_conta = :status WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();;
    }

    #Efetuar Login
    public function login()
    {
        $sql = "SELECT u.*, CASE 
            WHEN u.tipo_usuario = 'Usuário' THEN SUBSTRING_INDEX(u.nome_usuario, ' ', 1)
            WHEN u.tipo_usuario = 'Instrutor' THEN SUBSTRING_INDEX(u.nome_usuario, ' ', 1)
            WHEN u.tipo_usuario = 'Atleta' THEN SUBSTRING_INDEX(u.nome_usuario, ' ', 1)
            WHEN u.tipo_usuario = 'Administrador' THEN 'Admin'
        END AS nome_usuario FROM  $this->table u
        LEFT JOIN atleta atle ON u.id_usuario = atle.fk_id_usuario
        LEFT JOIN instrutor inst ON u.id_usuario = inst.fk_id_usuario
         WHERE u.email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_OBJ);
            if (password_verify($this->senha, $usuario->senha)) {
                $_SESSION['user_id'] = $usuario->id_usuario;
                $_SESSION['user_name'] = $usuario->nome_usuario;
                $_SESSION['tipo_usuario'] = $usuario->tipo_usuario;
                $_SESSION['user_email'] = $usuario->email;
                $_SESSION['ultimaAtividade'] = time();
                $redirect_url = $_POST['redirect'] ?? 'bemVindo.php';
                header("Location: $redirect_url");
                exit();
            }
        }

        return "Usuário ou Senha incorreta. Por favor, tente novamente.";
    }

    //Efetuar Logoff

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    public function contaExcluida()
    {
        session_unset();
        session_destroy();
        echo "<script>alert('Conta excluida com sucesso.'); window.location.href='index.php';</script>";
        exit();
    }
    #Expirar 

    public function sessaoExpirou()
    {
        $tempo = 1800; // 30 minutos de inatividade
        if (isset($_SESSION['ultimaAtividade']) && (time() - $_SESSION['ultimaAtividade']) > $tempo) {
            $this->logout();
            return true;
        }
        $_SESSION['ultimaAtividade'] = time(); // Atualiza a hora da última atividade
        return false;
    }

    public function verificarNivelAcesso(array $nivelNecessario)
    {
        // Verifica se o nível de acesso do usuário atende ao nível necessário
        if (isset($_SESSION['tipo_usuario']) && in_array($_SESSION['tipo_usuario'], $nivelNecessario)) {
            return true; // Usuário tem permissão
        }

        return false; // Usuário não tem permissão

    }

    public function solicitarRecuperacaoSenha($email, $mensagem, $assunto)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';
        try {
            // Verifica se o e-mail está cadastrado
            $sql = "SELECT id_usuario FROM $this->table WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_OBJ);

                // Gera token seguro
                $token = bin2hex(random_bytes(32));
                $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Insere token na tabela de recuperação
                $sql = "INSERT INTO RecuperacaoSenha (idUsuarioFK, tokenRecuperacaoSenha, expiraRecuperacaoSenha) 
                    VALUES (:idUsuarioFK, :tokenRecuperacaoSenha, :expiraRecuperacaoSenha)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':idUsuarioFK', $usuario->id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(':tokenRecuperacaoSenha', $token, PDO::PARAM_STR);
                $stmt->bindParam(':expiraRecuperacaoSenha', $expira_em, PDO::PARAM_STR);
                $stmt->execute();

                // Monta link de recuperação
                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                $dominio = $_SERVER['HTTP_HOST'];
                $caminho = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

                $link = "$protocolo://$dominio$caminho/reset_senha.php?token=$token";

                // Configura PHPMailer
                $mail = new PHPMailer(true);
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                try {
                    $config = parse_ini_file(__DIR__ . '/../config.ini', true)['email'];
                    // Configurações do servidor
                    $mail->isSMTP();
                    $mail->Host = $config['Host'];
                    $mail->SMTPAuth = $config['SMTPAuth'];
                    $mail->Username = $config['Username'];
                    $mail->Password = $config['Password'];
                    $mail->SMTPSecure = $config['SMTPSecure'];
                    $mail->Port = $config['Port'];                           

                    // Remetente e destinatário
                    $mail->setFrom($config['Username'], 'Cooperativa Aliança Marcial');
                    $mail->addAddress($email);

                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = $assunto;
                    $mail->Body = "
                    <p>Olá $mensagem</p>
                    <p>Clique no link abaixo para criar uma nova senha:</p>
                    <p><a href='$link'>$link</a></p>
                    <p>Este link expira em 1 hora.</p>
                    <p>Se você não solicitou isso, ignore este e-mail.</p>";

                    $mail->AltBody = "Olá,\n\nAcesse o link para redefinir sua senha: $link\n\nEste link expira em 1 hora.";

                    $mail->send();
                    return true;

                } catch (Exception $e) {
                    error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
                    return false;
                }

            } else {
                return false; // E-mail não encontrado
            }

        } catch (PDOException $e) {
            error_log('Erro em solicitarRecuperacaoSenha: ' . $e->getMessage());
            return false;
        }
    }


    public function redefinirSenha($token, $novaSenha)
    {
        try {
            // Verifica o token
            $sql = "SELECT idUsuarioFK, ExpiraRecuperacaoSenha FROM RecuperacaoSenha WHERE tokenRecuperacaoSenha = :tokenRecuperacaoSenha";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tokenRecuperacaoSenha', $token, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $dados = $stmt->fetch(PDO::FETCH_OBJ);
                if (strtotime($dados->ExpiraRecuperacaoSenha) >= time()) {
                    // Atualiza a senha
                    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
                    $sql = "UPDATE $this->table SET senha = :novaSenha WHERE id_usuario = :id_usuario";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':novaSenha', $novaSenhaHash, PDO::PARAM_STR);
                    $stmt->bindParam(':id_usuario', $dados->idUsuarioFK, PDO::PARAM_INT);
                    $stmt->execute();
                    // Remove o token usado
                    $sql = "DELETE FROM RecuperacaoSenha WHERE tokenRecuperacaoSenha = :tokenRecuperacaoSenha";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':tokenRecuperacaoSenha', $token, PDO::PARAM_STR);
                    $stmt->execute();

                    return true;
                } else {
                    return false; // Token expirado
                }
            } else {
                return false; // Token inválido
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function contaInvalida($email, $mensagem, $assunto)
    {
        require __DIR__ . '/../PHPMailer/src/Exception.php';
        require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require __DIR__ . '/../PHPMailer/src/SMTP.php';
        try {
            // Verifica se o e-mail está cadastrado
            $sql = "SELECT id_usuario FROM $this->table WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_OBJ);

                // Configura PHPMailer
                $mail = new PHPMailer(true);
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                try {
                    $config = parse_ini_file(__DIR__ . '/../config.ini', true)['email'];
                    // Configurações do servidor
                    $mail->isSMTP();
                    $mail->Host = $config['Host'];
                    $mail->SMTPAuth = $config['SMTPAuth'];
                    $mail->Username = $config['Username'];
                    $mail->Password = $config['Password'];
                    $mail->SMTPSecure = $config['SMTPSecure'];
                    $mail->Port = $config['Port'];                            

                    // Remetente e destinatário
                    $mail->setFrom($config['Username'], 'Cooperativa Aliança Marcial');
                    $mail->addAddress($email);

                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = $assunto;
                    $mail->Body = "
                    <p>Olá $mensagem</p>
                    <p>Se você não solicitou isso, ignore este e-mail.</p>";

                    $mail->send();
                    return true;

                } catch (Exception $e) {
                    error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
                    return false;
                }

            } else {
                return false; // E-mail não encontrado
            }

        } catch (PDOException $e) {
            error_log('Erro em contaInvalida: ' . $e->getMessage());
            return false;
        }
    }
}