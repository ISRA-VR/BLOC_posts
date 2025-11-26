<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

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

    public function create($nombre, $email, $password, $rol = 'autor') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => $hash,
                ':rol' => $rol
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}