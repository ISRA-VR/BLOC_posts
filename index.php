<?php
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', 
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {

        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function controller_path($name) {
    return __DIR__ . "/controllers/{$name}.php";
}
function model_path($name) {
    return __DIR__ . "/models/{$name}.php";
}

$rawController = isset($_GET['controller']) ? trim($_GET['controller']) : 'posts';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

$rawController = strtolower(preg_replace('/[^a-z0-9_]/i', '', $rawController));
$action = strtolower(preg_replace('/[^a-z0-9_]/i', '', $action));

$candidates = [];

$candidates[] = ucfirst($rawController) . 'Controller';

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

require_once $foundFile;

if (!class_exists($foundClass)) {
    header("HTTP/1.0 500 Internal Server Error");
    echo "La clase del controlador '{$foundClass}' no existe dentro de {$foundFile}. Verifica el nombre de la clase.";
    exit;
}

$controllerInstance = new $foundClass();

if (!method_exists($controllerInstance, $action)) {
    header("HTTP/1.0 404 Not Found");
    echo "Acción no encontrada.";
    exit;
}

$controllerInstance->{$action}();