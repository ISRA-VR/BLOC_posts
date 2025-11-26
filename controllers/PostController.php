<?php
require_once __DIR__ . '/../models/Post.php';

class PostController {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    private function checkAuth() {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        return $_SESSION['user'];
    }

    private function verifyCsrfOrDie($token) {
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$token)) {
            $_SESSION['flash'] = "Token CSRF inválido.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }
    }

    public function index() {
        $user = $this->checkAuth();
        if ($user['rol'] === 'admin') {
            $posts = $this->postModel->all();
        } else {
            $posts = $this->postModel->allByUser($user['id']);
        }
        require __DIR__ . '/../views/posts/index.php';
    }

    public function create() {
        $user = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/posts/create.php';
            return;
        }

        // POST -> verificar CSRF
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');

        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');

        if (!$titulo || !$contenido) {
            $_SESSION['flash'] = "Completa todos los campos.";
            header('Location: index.php?controller=post&action=create');
            exit;
        }

        $created = $this->postModel->create($user['id'], $titulo, $contenido);
        if ($created) {
            $_SESSION['flash'] = "Post creado correctamente.";
            header('Location: index.php?controller=posts&action=index');
        } else {
            $_SESSION['flash'] = "Error al crear post.";
            header('Location: index.php?controller=post&action=create');
        }
    }

    public function edit() {
        $user = $this->checkAuth();
        $id = intval($_GET['id'] ?? 0);
        $post = $this->postModel->find($id);
        if (!$post) {
            $_SESSION['flash'] = "Post no encontrado.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        if ($user['rol'] !== 'admin' && $post['usuario_id'] != $user['id']) {
            $_SESSION['flash'] = "No tienes permiso para editar este post.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/posts/edit.php';
            return;
        }

        // POST -> verificar CSRF
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');

        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');

        if (!$titulo || !$contenido) {
            $_SESSION['flash'] = "Completa todos los campos.";
            header("Location: index.php?controller=post&action=edit&id={$id}");
            exit;
        }

        $updated = $this->postModel->update($id, $titulo, $contenido);
        if ($updated) {
            $_SESSION['flash'] = "Post actualizado.";
        } else {
            $_SESSION['flash'] = "No se realizaron cambios o hubo un error.";
        }
        header('Location: index.php?controller=posts&action=index');
    }

    public function delete() {
        $user = $this->checkAuth();

        // Ahora delete debe ser POST (más seguro que GET)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash'] = "Método no permitido.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        // Verificar CSRF
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');

        $id = intval($_POST['id'] ?? 0);
        $post = $this->postModel->find($id);
        if (!$post) {
            $_SESSION['flash'] = "Post no encontrado.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        if ($user['rol'] !== 'admin' && $post['usuario_id'] != $user['id']) {
            $_SESSION['flash'] = "No tienes permiso para eliminar este post.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        $deleted = $this->postModel->delete($id);
        $_SESSION['flash'] = $deleted ? "Post eliminado." : "Error al eliminar post.";
        header('Location: index.php?controller=posts&action=index');
    }
}