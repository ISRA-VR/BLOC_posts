<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 1. Connect to MySQL server (no DB selected)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 2. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS miblogpro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Base de datos 'miblogpro' creada o ya existente.\n";
    
    // 3. Connect to the new DB
    $pdo->exec("USE miblogpro");
    
    // 4. Create Users Table
    $sqlUsers = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        rol ENUM('admin', 'autor') DEFAULT 'autor',
        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sqlUsers);
    echo "Tabla 'usuarios' creada.\n";
    
    // 5. Create Posts Table
    $sqlPosts = "CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        titulo VARCHAR(255) NOT NULL,
        contenido TEXT NOT NULL,
        imagen VARCHAR(255) NULL,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )";
    $pdo->exec($sqlPosts);
    echo "Tabla 'posts' creada.\n";
    
    // 6. Create Default Admin User
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = 'admin@example.com'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $pass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES ('Admin', 'admin@example.com', :pass, 'admin')");
        $stmt->execute([':pass' => $pass]);
        echo "Usuario admin creado (Email: admin@example.com, Pass: admin123).\n";
    } else {
        echo "Usuario admin ya existe.\n";
    }
    
} catch (PDOException $e) {
    die("Error DB: " . $e->getMessage());
}
