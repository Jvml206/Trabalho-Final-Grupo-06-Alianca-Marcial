<?php

class Academia extends CRUD
{
    protected $table = "academia";
    private $id_academia;
    private $nome_fantasia;
    private $razao_social;
    private $cnpj;
    private $telefone;
    private $endereco;
    private $bairro;
    private $cidade;
    private $cep;
    private $estado;
    private $instagram;
    private $email;
    private $logo;

    public function getIdAcademia()
    {
        return $this->id_academia;
    }

    public function setIdAcademia($id_academia)
    {
        $this->id_academia = $id_academia;
    }

    public function getNomeFantasia()
    {
        return $this->nome_fantasia;
    }

    public function setNomeFantasia($nome_fantasia)
    {
        $this->nome_fantasia = $nome_fantasia;
    }

    public function getRazaoSocial()
    {
        return $this->razao_social;
    }

    public function setRazaoSocial($razao_social)
    {
        $this->razao_social = $razao_social;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }
    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }
    public function getCep()
    {
        return $this->cep;
    }

    public function setCep($cep)
    {
        $this->cep = $cep;
    }
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getInstagram()
    {
        return $this->instagram;
    }

    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (nome_fantasia, razao_social, cnpj, telefone, endereco, bairro, cidade, cep, estado, instagram, email, logo)
                VALUES (:nome_fantasia, :razao_social, :cnpj, :telefone, :endereco, :bairro, :cidade, :cep, :estado, :instagram, :email, :logo)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
        $stmt->bindParam(':razao_social', $this->razao_social);
        $stmt->bindParam(':cnpj', $this->cnpj);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':endereco', $this->endereco);
        $stmt->bindParam(':bairro', $this->bairro);
        $stmt->bindParam(':cidade', $this->cidade);
        $stmt->bindParam(':cep', $this->cep);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':instagram', $this->instagram);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':logo', $this->logo);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_fantasia = :nome_fantasia, 
                    razao_social = :razao_social, 
                    cnpj = :cnpj, 
                    telefone = :telefone, 
                    endereco = :endereco, 
                    bairro = :bairro, 
                    cidade = :cidade, 
                    cep = :cep,
                    estado = :estado, 
                    instagram = :instagram,
                    email = :email,
                    logo = :logo
                WHERE $campo = :id_academia";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
        $stmt->bindParam(':razao_social', $this->razao_social);
        $stmt->bindParam(':cnpj', $this->cnpj);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':endereco', $this->endereco);
        $stmt->bindParam(':bairro', $this->bairro);
        $stmt->bindParam(':cidade', $this->cidade);
        $stmt->bindParam(':cep', $this->cep);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':instagram', $this->instagram);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':logo', $this->logo);
        $stmt->bindParam(':id_academia', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}