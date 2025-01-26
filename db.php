<?php

class Database {
    private string $dsn;
    private string $host = 'postgres';
    private string $dbname = 'to_do_list';
    private string $username = 'root';
    private string $password = 'root';
    private PDO $conn;

    public function __construct()
    {
        $this->dsn = "pgsql:host=$this->host;port=5432;dbname=$this->dbname;";
        try {
            $this->conn = new PDO($this->dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTable();
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    private function createTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS tasks (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            completed BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}