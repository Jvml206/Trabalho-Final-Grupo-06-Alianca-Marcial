<?php

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
    private $fk_id_academia;
    private $fk_id_instrutor;
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

    public function getFkIdAtleta()
    {
        return $this->fk_id_atleta;
    }
    public function setFkIdAtleta($fk_id_atleta)
    {
        $this->fk_id_atleta = $fk_id_atleta;
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
        return $stmt->execute();
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
                    fk_id_atleta = :fk_id_atleta
                WHERE $campo = :id_pedido_ajuda";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':valor_necessario', $this->valor_necessario);
        $stmt->bindParam(':valor_atingido', $this->valor_atingido);
        $stmt->bindParam(':pix', $this->pix);
        $stmt->bindParam(':imagem', $this->imagem);
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

}
