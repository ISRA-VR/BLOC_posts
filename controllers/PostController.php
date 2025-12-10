<?php
require_once __DIR__ . '/../models/Post.php';

class PostController {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    // Middleware: Verifica si el usuario está logueado
    private function checkAuth() {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        return $_SESSION['user'];
    }

    // Middleware: Verifica el token CSRF para formularios POST
    private function verifyCsrfOrDie($token) {
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$token)) {
            $_SESSION['flash'] = "Token CSRF inválido.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }
    }

    // Acción: Listar posts (Página principal)
    public function index() {
        $user = $this->checkAuth();
        
        // Si es admin ve todo, si no, solo sus posts
        if ($user['rol'] === 'admin') {
            $posts = $this->postModel->all();
        } else {
            $posts = $this->postModel->allByUser($user['id']);
        }
        
        // Carga la vista y le pasa la variable $posts
        require __DIR__ . '/../views/posts/index.php';
    }

    // Acción: Crear post
    public function create() {
        $user = $this->checkAuth();

        // Si es GET, mostramos el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/posts/create.php';
            return;
        }

        // Si es POST, procesamos el formulario
        $this->verifyCsrfOrDie($_POST['csrf_token'] ?? '');

        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');
        $imagen = null;

        // Manejo de subida de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileName = $_FILES['imagen']['name'];
            $fileSize = $_FILES['imagen']['size'];
            $fileType = $_FILES['imagen']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadDir . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imagen = 'uploads/' . $newFileName;
                } else {
                    $_SESSION['flash'] = "Error al mover el archivo subido.";
                    header('Location: index.php?controller=post&action=create');
                    exit;
                }
            } else {
                $_SESSION['flash'] = "Tipo de archivo no permitido. Solo JPG, GIF, PNG, WEBP.";
                header('Location: index.php?controller=post&action=create');
                exit;
            }
        }

        if (!$titulo || !$contenido) {
            $_SESSION['flash'] = "Completa todos los campos.";
            header('Location: index.php?controller=post&action=create');
            exit;
        }

        $created = $this->postModel->create($user['id'], $titulo, $contenido, $imagen);
        if ($created) {
            $_SESSION['flash'] = "Post creado correctamente.";
            header('Location: index.php?controller=posts&action=index');
        } else {
            $_SESSION['flash'] = "Error al crear post.";
            header('Location: index.php?controller=post&action=create');
        }
    }

    // Acción: Editar post
    public function edit() {
        $user = $this->checkAuth();
        $id = intval($_GET['id'] ?? 0);
        
        // Buscamos el post para verificar que existe y permisos
        $post = $this->postModel->find($id);
        if (!$post) {
            $_SESSION['flash'] = "Post no encontrado.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        // Verificamos propiedad (solo el autor o admin pueden editar)
        if ($user['rol'] !== 'admin' && $post['usuario_id'] != $user['id']) {
            $_SESSION['flash'] = "No tienes permiso para editar este post.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

        // GET: Mostrar formulario con datos actuales
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/posts/edit.php';
            return;
        }

        // POST: Guardar cambios
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

    // Acción: Eliminar post
    public function delete() {
        $user = $this->checkAuth();

        // Solo permitimos eliminar vía POST (seguridad)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash'] = "Método no permitido.";
            header('Location: index.php?controller=posts&action=index');
            exit;
        }

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