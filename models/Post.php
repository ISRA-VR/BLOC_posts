<?php
require_once __DIR__ . '/../config/Database.php';

class Post {
    private $db;

    public function __construct() {
        // Al instanciar el modelo, conectamos a la BD
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtener todos los posts (para la página principal)
    public function all($q = null, $page = 1, $perPage = 10, &$total = 0) {
        $offset = max(0, ($page - 1) * $perPage);
        $where = '';
        $params = [];
        if ($q) {
            $where = 'WHERE p.titulo LIKE :q1 OR p.contenido LIKE :q2 OR u.nombre LIKE :q3';
            $params[':q1'] = '%' . $q . '%';
            $params[':q2'] = '%' . $q . '%';
            $params[':q3'] = '%' . $q . '%';
        }
        $countSql = "SELECT COUNT(*) as cnt FROM posts p JOIN usuarios u ON p.usuario_id = u.id $where";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)($countStmt->fetch()['cnt'] ?? 0);

        $perPage = (int)$perPage; $offset = (int)$offset;
        $sql = "SELECT p.*, u.nombre as autor_nombre FROM posts p JOIN usuarios u ON p.usuario_id = u.id $where ORDER BY p.fecha_creacion DESC LIMIT $perPage OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v, PDO::PARAM_STR); }
        $stmt->execute();
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
    public function update($id, $titulo, $contenido, $imagen = null) {
        $sql = "UPDATE posts SET titulo = :titulo, contenido = :contenido";

        // Si se proporciona una nueva imagen, incluirla en la consulta
        if ($imagen) {
            $sql .= ", imagen = :imagen";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':id', $id);

        if ($imagen) {
            $stmt->bindParam(':imagen', $imagen);
        }

        return $stmt->execute();
    }

    // Eliminar un post
    public function delete($id) {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}