<?php
require_once __DIR__ . '/../config/Database.php';

class Post {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function all() {
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.fecha_creacion DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function allByUser($userId) {
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id WHERE p.usuario_id = :uid ORDER BY p.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($userId, $titulo, $contenido) {
        $sql = "INSERT INTO posts (usuario_id, titulo, contenido) VALUES (:uid, :titulo, :contenido)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':uid' => $userId,
            ':titulo' => $titulo,
            ':contenido' => $contenido
        ]);
    }

    public function update($id, $titulo, $contenido) {
        $sql = "UPDATE posts SET titulo = :titulo, contenido = :contenido WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':contenido' => $contenido,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}