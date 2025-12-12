<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Post.php';

class AdminController {
    private $userModel;
    private $postModel;

    public function __construct() {
        $this->userModel = new User();
        $this->postModel = new Post();
    }

    private function checkAdmin() {
        if (empty($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['flash'] = "Acceso restringido: se requiere rol administrador.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }
    }

    private function verifyCsrfOrDie($token) {
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$token)) {
            $_SESSION['flash'] = "Token CSRF inválido.";
            header('Location: index.php?controller=admin&action=index');
            exit;
        }
    }

    // Dashboard: resumen
    public function index() {
        $this->checkAdmin();
        $totalUsers = 0; $totalPosts = 0;
        $users = $this->userModel->all(null, 1, 5, $totalUsers);
        $posts = $this->postModel->all(null, 1, 5, $totalPosts);

        $stats = [
            'usuarios' => $totalUsers,
            'usuarios_suspendidos' => array_sum(array_map(fn($u) => (int)$u['suspendido'], $users)),
            'posts' => $totalPosts,
        ];

        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // Gestión de usuarios
    public function users() {
        $this->checkAdmin();
        $q = trim($_GET['q'] ?? '') ?: null;
        $page = max(1, intval($_GET['page'] ?? 1));
        $per = max(1, min(50, intval($_GET['per'] ?? 10)));
        $total = 0;
        $users = $this->userModel->all($q, $page, $per, $total);
        $pagination = [
            'page' => $page,
            'per' => $per,
            'total' => $total,
            'pages' => max(1, (int)ceil($total / $per))
        ];
        require __DIR__ . '/../views/admin/users.php';
    }

    public function setrole() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=users');
            return;
        }
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');
        $id = intval($_POST['id'] ?? 0);
        $rol = $_POST['rol'] ?? 'autor';
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID inválido';
        } else {
            $_SESSION['flash'] = $this->userModel->updateRole($id, $rol) ? 'Rol actualizado.' : 'Error al actualizar rol.';
        }
        header('Location: index.php?controller=admin&action=users');
    }

    public function suspend() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=users');
            return;
        }
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');
        $id = intval($_POST['id'] ?? 0);
        $suspend = intval($_POST['suspend'] ?? 1) === 1;
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID inválido';
        } else {
            // Evitar suspender al propio admin o a cualquier usuario con rol admin
            $target = $this->userModel->findById($id);
            if (!$target) {
                $_SESSION['flash'] = 'Usuario no encontrado.';
            } elseif ($id === ($_SESSION['user']['id'] ?? -1)) {
                $_SESSION['flash'] = 'No puedes suspender tu propia cuenta de administrador.';
            } elseif (($target['rol'] ?? '') === 'admin') {
                $_SESSION['flash'] = 'No se puede suspender una cuenta con rol administrador.';
            } else {
                $_SESSION['flash'] = $this->userModel->setSuspended($id, $suspend) ? ($suspend ? 'Usuario suspendido.' : 'Usuario reactivado.') : 'Error en la operación.';
            }
        }
        header('Location: index.php?controller=admin&action=users');
    }

    public function deleteuser() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=users');
            return;
        }
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID inválido';
        } else {
            $target = $this->userModel->findById($id);
            if (!$target) {
                $_SESSION['flash'] = 'Usuario no encontrado.';
            } elseif ($id === ($_SESSION['user']['id'] ?? -1)) {
                $_SESSION['flash'] = 'No puedes eliminar tu propia cuenta de administrador.';
            } elseif (($target['rol'] ?? '') === 'admin') {
                $_SESSION['flash'] = 'No se puede eliminar una cuenta con rol administrador.';
            } else {
                $_SESSION['flash'] = $this->userModel->delete($id) ? 'Usuario eliminado.' : 'Error al eliminar usuario.';
            }
        }
        header('Location: index.php?controller=admin&action=users');
    }

    // Gestión de posts
    public function posts() {
        $this->checkAdmin();
        $q = trim($_GET['q'] ?? '') ?: null;
        $page = max(1, intval($_GET['page'] ?? 1));
        $per = max(1, min(50, intval($_GET['per'] ?? 10)));
        $total = 0;
        $posts = $this->postModel->all($q, $page, $per, $total);
        $pagination = [
            'page' => $page,
            'per' => $per,
            'total' => $total,
            'pages' => max(1, (int)ceil($total / $per))
        ];
        require __DIR__ . '/../views/admin/posts.php';
    }

    public function deletepost() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=admin&action=posts');
            return;
        }
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID inválido';
        } else {
            $_SESSION['flash'] = $this->postModel->delete($id) ? 'Post eliminado.' : 'Error al eliminar post.';
        }
        header('Location: index.php?controller=admin&action=posts');
    }
}
