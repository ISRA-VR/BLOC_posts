<?php
class Database {
    
    private $host = 'localhost';
    private $db_name = 'miblogpro';
    private $username = 'root';
    private $password = '';

    public $conn = null;
    public $connected = false;

    public function getConnection($mostrarMensaje = false) {
        $this->conn = null;
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en caso de error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devolver arrays asociativos por defecto
                PDO::ATTR_EMULATE_PREPARES => false, // Usar sentencias preparadas nativas (más seguro)
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
                throw new Exception("No se pudo conectar a la base de datos.");
            }
        }
        return $this->conn;
    }
    public function connectionStatus() {
        return $this->connected ? "Conectado" : "No conectado";
    }
}