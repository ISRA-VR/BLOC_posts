<?php
require_once __DIR__ . '/../config/Database.php';

// ==========================================
// MODELO POST
// ==========================================
// Se encarga de todas las operaciones de base de datos relacionadas con los Posts.
// CRUD: Create, Read, Update, Delete
class Post {
    private $db;

    public function __construct() {
        // Al instanciar el modelo, conectamos a la BD
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener todos los posts (para la página principal)
    public function all() {
        // JOIN: Unimos la tabla posts con usuarios para obtener el nombre del autor
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id ORDER BY p.fecha_creacion DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Obtener posts de un usuario específico
    public function allByUser($userId) {
        // Usamos parámetros nombrados (:uid) para prevenir inyección SQL
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id WHERE p.usuario_id = :uid ORDER BY p.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    // Buscar un post por su ID
    public function find($id) {
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Crear un nuevo post
    public function create($userId, $titulo, $contenido, $imagen = null) {
        $sql = "INSERT INTO posts (usuario_id, titulo, contenido, imagen) VALUES (:uid, :titulo, :contenido, :imagen)";
        $stmt = $this->db->prepare($sql);
        // execute() devuelve true si la inserción fue exitosa
        return $stmt->execute([
            ':uid' => $userId,
            ':titulo' => $titulo,
            ':contenido' => $contenido,
            ':imagen' => $imagen
        ]);
    }

    // Actualizar un post existente
    public function update($id, $titulo, $contenido) {
        $sql = "UPDATE posts SET titulo = :titulo, contenido = :contenido WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':contenido' => $contenido,
            ':id' => $id
        ]);
    }

    // Eliminar un post
    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}