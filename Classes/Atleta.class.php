<?php

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
    private $fk_id_usuario;

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
                (nome_atleta, data_nascimento, biografia, sexo, peso, esporte, categoria, fk_id_usuario) 
                VALUES (:nome_atleta, :data_nascimento, :biografia, :sexo, :peso, :esporte, :categoria, :fk_id_usuario)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_atleta', $this->nome_atleta);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':biografia', $this->biografia);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':peso', $this->peso);
        $stmt->bindParam(':esporte', $this->esporte);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':fk_id_usuario', $this->fk_id_usuario);
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
                    fk_id_usuario = :fk_id_usuario
                WHERE $campo = :id_pedido_ajuda";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_atleta', $this->nome_atleta);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':biografia', $this->biografia);
        $stmt->bindParam(':sexo', $this->sexo);
        $stmt->bindParam(':peso', $this->peso);
        $stmt->bindParam(':esporte', $this->esporte);
        $stmt->bindParam(':categoria', $this->categoria);
        $stmt->bindParam(':fk_id_usuario', $this->fk_id_usuario);
        $stmt->bindParam(":id_pedido_ajuda", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
