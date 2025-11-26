<?php
// index.php (front controller)
// Versión robusta: seguridad básica de sesión + router tolerante plural/singular
// Coloca este archivo en la raíz del proyecto (/BLOC_posts/index.php)

// ---------- Parámetros de sesión / seguridad ----------
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

// Ajustar cookies de sesión antes de session_start()
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // deja vacío para el host actual
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Iniciar sesión
session_start();

// Generar token CSRF si no existe (básico)
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        // fallback si random_bytes no está disponible
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

// Mostrar errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---------- Helpers de rutas ----------
function controller_path($name) {
    return __DIR__ . "/controllers/{$name}.php";
}
function model_path($name) {
    return __DIR__ . "/models/{$name}.php";
}

// ---------- Router tolerante ----------
$rawController = isset($_GET['controller']) ? trim($_GET['controller']) : 'posts';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

// Normalizar entrada (permitir solo caracteres alfanuméricos y guión bajo)
$rawController = strtolower(preg_replace('/[^a-z0-9_]/i', '', $rawController));
$action = strtolower(preg_replace('/[^a-z0-9_]/i', '', $action));

// Construir lista de candidatos de clase/archivo en orden de preferencia
$candidates = [];

// Convención 1: ucfirst(raw) + 'Controller' -> e.g. 'PostsController'
$candidates[] = ucfirst($rawController) . 'Controller';
// Convención 2: singularizar quitando 's' final y usar ucfirst + 'Controller' -> 'PostController'
if (substr($rawController, -1) === 's') {
    $singular = rtrim($rawController, 's');
    if ($singular !== '') {
        $candidates[] = ucfirst($singular) . 'Controller';
    }
}
// Convención 3: intentar ucfirst(rawController without trailing s) + 'Controller' (por si no habia s)
$candidates[] = ucfirst(rtrim($rawController, 's')) . 'Controller';
// Convención 4: usar rawController tal cual capitalizado y con 's' añadida (por si se nombró PostsController manualmente)
$candidates[] = ucfirst($rawController) . 'sController';

// Evitar duplicados
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

// Si no encontramos controlador, devolver mensaje claro (debug amigable)
if (!$foundFile) {
    header("HTTP/1.0 404 Not Found");
    echo "<h2>Controlador no encontrado.</h2>";
    echo "<p>Se buscaron las clases (y archivos) en /controllers/:</p><ul>";
    foreach ($candidates as $c) {
        echo "<li><code>" . htmlspecialchars($c) . "</code> -> archivo esperado: <code>" . htmlspecialchars(controller_path($c)) . "</code></li>";
    }
    echo "</ul>";
    echo "<p>getcwd(): <strong>" . htmlspecialchars(getcwd()) . "</strong></p>";
    echo "<p>Contenido de /controllers/: <pre>" . htmlspecialchars(implode("\\n", scandir(__DIR__ . DIRECTORY_SEPARATOR . 'controllers'))) . "</pre></p>";
    exit;
}

// Incluir el archivo del controlador encontrado
require_once $foundFile;

// Verificar que la clase exista y tenga la acción solicitada
if (!class_exists($foundClass)) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "La clase del controlador '{$foundClass}' no existe dentro de {$foundFile}. Verifica el nombre de la clase.";
    exit;
}

$controllerInstance = new $foundClass();

// Ejecutar la acción si existe
if (!method_exists($controllerInstance, $action)) {
    header("HTTP/1.0 404 Not Found");
    echo "Acción no encontrada.";
    exit;
}

// Llamar al método
$controllerInstance->{$action}();