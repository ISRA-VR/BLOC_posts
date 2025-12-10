<?php
require_once __DIR__ . '/../models/User.php';

// ==========================================
// CONTROLADOR DE AUTENTICACIÓN
// ==========================================
// Maneja el registro, inicio de sesión y cierre de sesión.
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Acción: Iniciar sesión
    public function login() {
        // GET: Mostrar formulario
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/auth/login.php';
            return;
        }
        
        // POST: Procesar login
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

        // Buscamos usuario y verificamos contraseña
        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash'] = "Credenciales inválidas.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Seguridad: Regenerar ID de sesión para evitar fijación de sesión
        session_regenerate_id(true);

        // Guardamos datos del usuario en sesión
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ];

        // Rotamos el token CSRF por seguridad
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: index.php?controller=posts&action=index');
    }

    // Acción: Registro de usuario
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

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

    // Acción: Cerrar sesión
    public function logout() {
        // Limpiamos la sesión
        $_SESSION = [];
        
        // Borramos la cookie de sesión
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

        session_unset();
        session_destroy();

        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}