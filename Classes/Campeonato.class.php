<?php

class Campeonato extends CRUD
{
    protected $table = "campeonato";
    private $id_campeonato;
    private $nome_campeonato;
    private $data_inicio;
    private $data_fim;
    private $local;
    private $pais;
    private $cidade;
    private $esporte;

    public function getIdCampeonato()
    {
        return $this->id_campeonato;
    }

    public function setIdCampeonato($id_campeonato)
    {
        $this->id_campeonato = $id_campeonato;
    }

    public function getNomeCampeonato()
    {
        return $this->nome_campeonato;
    }

    public function setNomeCampeonato($nome_campeonato)
    {
        $this->nome_campeonato = $nome_campeonato;
    }

    public function getDataInicio()
    {
        return $this->data_inicio;
    }

    public function setDataInicio($data_inicio)
    {
        $this->data_inicio = $data_inicio;
    }

    public function getDataFim()
    {
        return $this->data_fim;
    }

    public function setDataFim($data_fim)
    {
        $this->data_fim = $data_fim;
    }

    
    public function getLocal()
    {
        return $this->local;
    }
    
    public function setLocal($local)
    {
        $this->local = $local;
    }

    public function getPais()
    {
        return $this->pais;
    }

    public function setPais($pais)
    {
        $this->pais = $pais;
    }
    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }
    public function getEsporte()
    {
        return $this->esporte;
    }

    public function setEsporte($esporte)
    {
        $this->esporte = $esporte;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (nome_campeonato, data_inicio, data_fim, local, pais, cidade, esporte) 
                VALUES (:nome_campeonato, :data_inicio, :data_fim, :local, :pais, :cidade, :esporte)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_campeonato', $this->nome_campeonato);
        $stmt->bindParam(':data_inicio', $this->data_inicio);
        $stmt->bindParam(':data_fim', $this->data_fim);
        $stmt->bindParam(':local', $this->local);
        $stmt->bindParam(':pais', $this->pais);
        $stmt->bindParam(':cidade', $this->cidade);
        $stmt->bindParam(':esporte', $this->esporte);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_campeonato = :nome_campeonato, 
                    data_inicio = :data_inicio, 
                    data_fim = :data_fim, 
                    local = :local, 
                    pais = :pais, 
                    cidade = :cidade, 
                    esporte = :esporte
                WHERE $campo = :id_campeonato";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_campeonato', $this->nome_campeonato);
        $stmt->bindParam(':data_inicio', $this->data_inicio);
        $stmt->bindParam(':data_fim', $this->data_fim);
        $stmt->bindParam(':local', $this->local);
        $stmt->bindParam(':pais', $this->pais);
        $stmt->bindParam(':cidade', $this->cidade);
        $stmt->bindParam(':esporte', $this->esporte);
        $stmt->bindParam(":id_campeonato", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
