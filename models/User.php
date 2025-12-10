<?php
require_once __DIR__ . '/../config/Database.php';

// ==========================================
// MODELO USUARIO
// ==========================================
class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Buscar usuario por email (para login)
    public function findByEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT id, nombre, email, rol, creado_en FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Crear nuevo usuario (Registro)
    public function create($nombre, $email, $password, $rol = 'autor') {
        // IMPORTANTE: Nunca guardar contraseñas en texto plano. Usamos password_hash.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => $hash, // Guardamos el hash, no la contraseña real
                ':rol' => $rol
            ]);
        } catch (PDOException $e) {
            // Probablemente error de email duplicado
            return false;
        }
    }
}