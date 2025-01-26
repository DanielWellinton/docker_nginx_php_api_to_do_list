<?php

require_once 'db.php';

class Task {
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database)->getConnection();
    }

    public function createTask($title, $description)
    {
        $query = "INSERT INTO tasks (title, description) VALUES (:title, :description);";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return ['status' => true, 'message' => 'Tarefa criada com sucesso!'];
        } else {
            return ['status' => false, 'message' => 'Erro ao criar tarefa.'];
        }
    }

    public function getTasks($page, $offset)
    {
        $query = "SELECT * FROM tasks ORDER BY id DESC LIMIT 5 OFFSET :offset;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalSql = "SELECT COUNT(*) AS total FROM tasks;";
        $totalStmt = $this->conn->query($totalSql);
        $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'tasks' => $tasks,
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / 5)
        ];
    }

    public function updateTask($id, $title, $description, $completed)
    {
        $query = "UPDATE tasks SET title = :title, description = :description, completed = :completed WHERE id = :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':completed', $completed);

        if ($stmt->execute()) {
            return ['status' => true, 'message' => 'Tarefa atualizada com sucesso!'];
        } else {
            return ['status' => false, 'message' => 'Erro ao atualizar tarefa.'];
        }
    }

    public function deleteTask($id)
    {
        $query = "DELETE FROM tasks WHERE id = :id;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ['status' => true, 'message' => 'Tarefa excluída com sucesso!'];
        } else {
            return ['status' => false, 'message' => 'Erro ao excluir tarefa.'];
        }
    }
}