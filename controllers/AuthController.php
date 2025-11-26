<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // GET -> mostrar formulario
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        // POST -> procesar login
        // Verificar CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $_SESSION['flash'] = "Token inválido. Intenta nuevamente.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['flash'] = "Completa todos los campos.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash'] = "Credenciales inválidas.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Autenticar: regenerar id de sesión para evitar fijación de sesión
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ];

        // Renovar token CSRF tras login
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: index.php?controller=posts&action=index');
    }

    public function register() {
        // GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        // POST -> Verificar CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $_SESSION['flash'] = "Token inválido. Intenta nuevamente.";
            header('Location: index.php?controller=auth&action=register');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if (!$nombre || !$email || !$password) {
            $_SESSION['flash'] = "Completa todos los campos.";
            header('Location: index.php?controller=auth&action=register');
            exit;
        }

        if ($password !== $password2) {
            $_SESSION['flash'] = "Las contraseñas no coinciden.";
            header('Location: index.php?controller=auth&action=register');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['flash'] = "Ya existe una cuenta con ese email.";
            header('Location: index.php?controller=auth&action=register');
            exit;
        }

        $created = $this->userModel->create($nombre, $email, $password);
        if ($created) {
            $_SESSION['flash'] = "Registro exitoso. Puedes iniciar sesión.";
            header('Location: index.php?controller=auth&action=login');
        } else {
            $_SESSION['flash'] = "Error al crear usuario.";
            header('Location: index.php?controller=auth&action=register');
        }
    }

    public function logout() {
        // Romper sesión de forma segura
        // 1. Limpiar datos de sesión
        $_SESSION = [];

        // 2. Borrar cookie de sesión en el cliente
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'] ?? '/',
            $params['domain'] ?? '',
            $params['secure'] ?? false,
            $params['httponly'] ?? true
        );

        // 3. Destruir la sesión
        session_unset();
        session_destroy();

        // 4. Redirigir al login
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}