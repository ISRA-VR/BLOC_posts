<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'blog_mvc';
    private $username = 'root';
    private $password = '';
    public $conn = null;
    public $connected = false;

    public function getConnection($mostrarMensaje = false) {
        $this->conn = null;
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            $this->connected = true;

            if ($mostrarMensaje) {
                echo "<div style='padding:10px;background:#e6ffed;border:1px solid #b6f0c6;color:#064e2b;margin:10px 0;'>Conexión a la base de datos establecida con éxito.</div>";
            }
        } catch (PDOException $exception) {
            $this->connected = false;
            $errorMsg = "Error de conexión: " . $exception->getMessage();
            if ($mostrarMensaje) {
                echo "<div style='padding:10px;background:#ffe6e6;border:1px solid #f0b6b6;color:#5a0b0b;margin:10px 0;'>$errorMsg</div>";
            } else {
                error_log($errorMsg);
            }
        }
        return $this->conn;
    }
    public function connectionStatus() {
        return $this->connected ? "Conectado" : "No conectado";
    }
}