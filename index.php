<?php
// ==========================================
// PUNTO DE ENTRADA PRINCIPAL (ROUTER)
// ==========================================
// Este archivo recibe TODAS las peticiones del usuario.
// Su trabajo es decidir qué Controlador y qué Acción ejecutar.

// 1. Configuración de seguridad para Cookies de Sesión
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,      // La cookie expira al cerrar el navegador
    'path' => '/',        // Disponible en todo el dominio
    'domain' => '', 
    'secure' => $secure,  // Solo enviar por HTTPS si está disponible
    'httponly' => true,   // No accesible vía JavaScript (protección XSS)
    'samesite' => 'Lax'   // Protección básica contra CSRF
]);

session_start(); // Iniciar o reanudar la sesión del usuario

// 2. Generación de Token CSRF (Cross-Site Request Forgery)
// Este token se usa en los formularios para asegurar que el envío viene de nuestra web.
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

// 3. Configuración de Errores (Solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Funciones auxiliares para rutas de archivos
function controller_path($name) {
    return __DIR__ . "/controllers/{$name}.php";
}
function model_path($name) {
    return __DIR__ . "/models/{$name}.php";
}

// 4. Enrutamiento (Routing)
// Leemos los parámetros de la URL: index.php?controller=posts&action=index
$rawController = isset($_GET['controller']) ? trim($_GET['controller']) : 'posts';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

// Limpieza básica de seguridad (solo letras, números y guiones bajos)
$rawController = strtolower(preg_replace('/[^a-z0-9_]/i', '', $rawController));
$action = strtolower(preg_replace('/[^a-z0-9_]/i', '', $action));

// 5. Búsqueda inteligente del Controlador
// Intentamos adivinar el nombre del archivo (ej. "posts" -> "PostController" o "PostsController")
$candidates = [];
$candidates[] = ucfirst($rawController) . 'Controller';

// Manejo de singulares/plurales (ej. "posts" -> busca "PostController")
if (substr($rawController, -1) === 's') {
    $singular = rtrim($rawController, 's');
    if ($singular !== '') {
        $candidates[] = ucfirst($singular) . 'Controller';
    }
}
$candidates[] = ucfirst(rtrim($rawController, 's')) . 'Controller';
$candidates[] = ucfirst($rawController) . 'sController';
$candidates = array_unique($candidates);

$foundClass = null;
$foundFile = null;
foreach ($candidates as $class) {
    $file = controller_path($class);
    if (file_exists($file)) {
        $foundClass = $class;
        $foundFile = $file;
        break;
    }
}

// Si no encontramos el archivo, mostramos error 404
if (!$foundFile) {
    header("HTTP/1.0 404 Not Found");
    echo "<h2>Controlador no encontrado.</h2>";
    // ... (código de depuración omitido para brevedad)
    exit;
}

// 6. Carga y Ejecución
require_once $foundFile; // Importamos el archivo del controlador

if (!class_exists($foundClass)) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "La clase del controlador '{$foundClass}' no existe.";
    exit;
}

// Instanciamos la clase (ej. new PostController())
$controllerInstance = new $foundClass();

// Verificamos si el método existe (ej. index(), create(), delete())
if (!method_exists($controllerInstance, $action)) {
    header("HTTP/1.0 404 Not Found");
    echo "Acción no encontrada.";
    exit;
}

// Ejecutamos la acción
$controllerInstance->{$action}();
