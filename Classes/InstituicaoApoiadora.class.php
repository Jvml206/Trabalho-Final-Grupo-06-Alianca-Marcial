<?php

class InstituicaoApoiadora extends CRUD
{
    protected $table = "instituicao_apoiadora";
    private $id_instituicao_apoiadora;
    private $nome_fantasia;    
    private $link;
    private $logo;

    public function getIdInstituicaoApoiadora()
    {
        return $this->id_instituicao_apoiadora;
    }

    public function setIdInstituicaoApoiadora($id_instituicao_apoiadora)
    {
        $this->id_instituicao_apoiadora = $id_instituicao_apoiadora;
    }

    public function getNomeFantasia()
    {
        return $this->nome_fantasia;
    }

    public function setNomeFantasia($nome_fantasia)
    {
        $this->nome_fantasia = $nome_fantasia;
    }

    public function getLink()
    {
        return $this->link;
    }
    public function setLink($link)
    {
        $this->link = $link;
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
                (nome_fantasia, link, logo) 
                VALUES (:nome_fantasia, :link, :logo)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':logo', $this->logo);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_fantasia = :nome_fantasia, 
                    link = :link,
                    logo = :logo
                WHERE $campo = :id_instituicao_apoiadora";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':logo', $this->logo);
        $stmt->bindParam(":id_instituicao_apoiadora", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
