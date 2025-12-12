<?php
require_once __DIR__ . '/../config/Database.php';

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
        $sql = "SELECT id, nombre, email, rol, suspendido, creado_en FROM usuarios WHERE id = :id LIMIT 1";
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

    // Listar todos los usuarios
    public function all($q = null, $page = 1, $perPage = 10, &$total = 0) {
        $offset = max(0, ($page - 1) * $perPage);
        $where = '';
        $params = [];
        if ($q) {
            $where = 'WHERE nombre LIKE :q1 OR email LIKE :q2';
            $params[':q1'] = '%' . $q . '%';
            $params[':q2'] = '%' . $q . '%';
        }
        // total
        $countSql = "SELECT COUNT(*) as cnt FROM usuarios $where";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)($countStmt->fetch()['cnt'] ?? 0);

        // Para compatibilidad con algunos drivers, interpolamos LIMIT/OFFSET como enteros ya validados
        $perPage = (int)$perPage; $offset = (int)$offset;
        $sql = "SELECT id, nombre, email, rol, suspendido, creado_en FROM usuarios $where ORDER BY creado_en DESC LIMIT $perPage OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) { $stmt->bindValue($k, $v, PDO::PARAM_STR); }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Actualizar rol de usuario
    public function updateRole($id, $rol) {
        $allowed = ['admin', 'autor'];
        if (!in_array($rol, $allowed, true)) return false;
        $sql = "UPDATE usuarios SET rol = :rol WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':rol' => $rol, ':id' => $id]);
    }

    // Suspender o reactivar usuario
    public function setSuspended($id, $suspendido) {
        $sql = "UPDATE usuarios SET suspendido = :suspendido WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':suspendido' => $suspendido ? 1 : 0, ':id' => $id]);
    }

    // Eliminar usuario
    public function delete($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}