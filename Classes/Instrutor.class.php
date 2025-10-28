<?php

class Instrutor extends CRUD
{
    protected $table = "instrutor";
    private $id_instrutor;
    private $nome_instrutor;
    private $data_nascimento;
    private $cpf;
    private $telefone;
    private $email;
    private $fk_id_academia;

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

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
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
    public function getFkIdAcademia()
    {
        return $this->fk_id_academia;
    }

    public function setFkIdAcademia($fk_id_academia)
    {
        $this->fk_id_academia = $fk_id_academia;
    }

    // Métodos CRUD
    public function add()
    {
        $sql = "INSERT INTO $this->table 
                (nome_instrutor, data_nascimento, cpf, telefone, email, fk_id_academia) 
                VALUES (:nome_instrutor, :data_nascimento, :cpf, :telefone, :email, :fk_id_academia)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_instrutor', $this->nome_instrutor);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':cpf', $this->cpf);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fk_id_academia', $this->fk_id_academia);
        return $stmt->execute();
    }

    public function update(string $campo, int $id)
    {
        $sql = "UPDATE $this->table 
                SET nome_instrutor = :nome_instrutor, 
                    data_nascimento = :data_nascimento, 
                    cpf = :cpf, 
                    telefone = :telefone, 
                    email = :email, 
                    fk_id_academia = :fk_id_academia
                WHERE $campo = :id_instrutor";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nome_instrutor', $this->nome_instrutor);
        $stmt->bindParam(':data_nascimento', $this->data_nascimento);
        $stmt->bindParam(':cpf', $this->cpf);
        $stmt->bindParam(':telefone', $this->telefone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fk_id_academia', $this->fk_id_academia, PDO::PARAM_INT);
        $stmt->bindParam(":id_instrutor", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
