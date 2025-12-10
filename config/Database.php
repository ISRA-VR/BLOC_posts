<?php
// ==========================================
// CLASE DE CONEXIÓN A BASE DE DATOS
// ==========================================
class Database {
    
    // Configuración LOCAL (XAMPP)
    private $host = 'localhost';
    private $db_name = 'miblogpro';
    private $username = 'root';
    private $password = '';
    
    // Configuración REMOTA (Comentada)
    // private $host = 'mysql.webcindario.com';
    // private $db_name = 'miblogpro';
    // private $username = 'miblogpro';
    // private $password = '2004Osva@';

    public $conn = null;
    public $connected = false;

    // Método para obtener la conexión PDO
    public function getConnection($mostrarMensaje = false) {
        $this->conn = null;
        try {
            // Opciones de configuración de PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en caso de error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devolver arrays asociativos por defecto
                PDO::ATTR_EMULATE_PREPARES => false, // Usar sentencias preparadas nativas (más seguro)
            ];

            // DSN (Data Source Name): Cadena de conexión
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
                // En producción, es mejor guardar el error en un log y no mostrarlo al usuario
                error_log($errorMsg);
                // Lanzamos la excepción para que el código que llama sepa que falló
                throw new Exception("No se pudo conectar a la base de datos.");
            }
        }
        return $this->conn;
    }
    public function connectionStatus() {
        return $this->connected ? "Conectado" : "No conectado";
    }
}