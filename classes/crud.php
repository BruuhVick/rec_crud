<?php

include_once('conexao/conexao.php');

$db = new Database();

class Crud{
    private $conn;
    private $table_name = "alunos";

    public function __construct($db){
        $this->conn = $db;
    }

    // Função para criar registros
    public function create($postValues){
        $nome = $postValues['nome'];
        $numero = $postValues['numero'];
        $turma = $postValues['turma'];
        $data = $postValues['data'];
        $professor = $postValues['professor'];

        $query = "INSERT INTO " . $this->table_name . " (nome, numero, turma, data, professor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $numero);
        $stmt->bindParam(3, $turma);
        $stmt->bindParam(4, $data);
        $stmt->bindParam(5, $professor);

        if ($stmt->execute()) {
            print "<script>alert('Cadastro ok!')</script>";
            print "<script>location.href='?action=read';</script>";
            return true;
        } else {
            return false;
        }
    }

    // Função para ler os registros
    public function read(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Função para atualizar registros
    public function update($postValues){
        $id = $postValues['id'];
        $nome = $postValues['nome'];
        $numero = $postValues['numero'];
        $turma = $postValues['turma'];
        $data = $postValues['data'];
        $professor = $postValues['professor'];

        if (empty($id) || empty($nome) || empty($numero) || empty($turma) || empty($data) || empty($professor)) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET nome = ?, numero = ?, turma = ?, data = ?, professor = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $numero);
        $stmt->bindParam(3, $turma);
        $stmt->bindParam(4, $data);
        $stmt->bindParam(5, $professor);
        $stmt->bindParam(6, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Função para obter um registro pelo ID e inseri-lo no formulário
    public function readOne($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para apagar registros
    public function delete($id){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
