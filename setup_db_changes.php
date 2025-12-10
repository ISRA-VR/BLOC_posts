<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = (new Database())->getConnection();
    
    // Check if column exists
    $check = $db->query("SHOW COLUMNS FROM posts LIKE 'imagen'");
    if ($check->rowCount() == 0) {
        $sql = "ALTER TABLE posts ADD COLUMN imagen VARCHAR(255) NULL AFTER contenido";
        $db->exec($sql);
        echo "Columna 'imagen' agregada correctamente.";
    } else {
        echo "La columna 'imagen' ya existe.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
