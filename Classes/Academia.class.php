<?php

class Academia extends CRUD
{
    protected $table = "academia";
    private $id_academia;
    private $nome_fantasia;
    private $razao_social;
    private $cnpj;
    private $link;
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

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
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
                (nome_fantasia, razao_social, cnpj, link, email, logo)
                VALUES (:nome_fantasia, :razao_social, :cnpj, :link, :email, :logo)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
        $stmt->bindParam(':razao_social', $this->razao_social);
        $stmt->bindParam(':cnpj', $this->cnpj);
        $stmt->bindParam(':link', $this->link);
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
                    link = :link,
                    email = :email,
                    logo = :logo
                WHERE $campo = :id_academia";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
        $stmt->bindParam(':razao_social', $this->razao_social);
        $stmt->bindParam(':cnpj', $this->cnpj);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':logo', $this->logo);
        $stmt->bindParam(':id_academia', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}