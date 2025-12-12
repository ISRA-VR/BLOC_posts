<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/auth/login.php';
            return;
        }
        
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

        // Bloquear usuarios suspendidos
        if (!empty($user['suspendido']) && intval($user['suspendido']) === 1) {
            $_SESSION['flash'] = "Tu cuenta está suspendida. Contacta al administrador.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol'],
            'suspendido' => $user['suspendido'] ?? 0
        ];

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // Redirigir según rol: admins al dashboard
        if (!empty($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === 'admin') {
            header('Location: index.php?controller=admin&action=index');
        } else {
            header('Location: index.php?controller=posts&action=index');
        }
    }

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

        // Validación de contraseña fuerte: 8+ chars, una mayúscula, un dígito y un símbolo
        $strongPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';
        if (!preg_match($strongPattern, $password)) {
            $_SESSION['flash'] = "La contraseña no cumple: mínima de 8 caracteres con mayúscula, número y símbolo.";
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
        $_SESSION = [];
        
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