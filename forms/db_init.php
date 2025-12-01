<?php
/**
 * Script para inicializar la base de datos automáticamente
 * Acceso: http://localhost:8888/Portfolio/forms/db_init.php
 */

$servername = "localhost";
$username = "root";
$password = "root";
$port = 3306;

// Conectar sin especificar BD (para crearla)
$conn = new mysqli($servername, $username, $password, "", $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// SQL para crear BD y tablas
$sql = "
CREATE DATABASE IF NOT EXISTS portfolio_contact;
USE portfolio_contact;

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message LONGTEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    read_status TINYINT(1) DEFAULT 0 COMMENT '0=no leído, 1=leído',
    replied_at DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at),
    INDEX idx_read_status (read_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT,
    status VARCHAR(50),
    error_message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    INDEX idx_message_id (message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// Ejecutar SQL
if ($conn->multi_query($sql)) {
    // Consumir todos los resultados
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Base de datos iniciada</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; margin: 0; padding: 20px; }
        .success { background: #4caf50; color: white; padding: 20px; border-radius: 5px; text-align: center; max-width: 500px; margin: 50px auto; }
        .success h2 { margin-top: 0; }
        a { color: white; text-decoration: none; }
    </style>
</head>
<body>
    <div class='success'>
        <h2>✅ Base de datos creada exitosamente</h2>
        <p>La BD 'portfolio_contact' y sus tablas han sido creadas.</p>
        <p><a href='view_messages.php'>Ver mensajes →</a></p>
    </div>
</body>
</html>";
} else {
    echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Error en la base de datos</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; margin: 0; padding: 20px; }
        .error { background: #f44336; color: white; padding: 20px; border-radius: 5px; text-align: center; max-width: 500px; margin: 50px auto; }
        .error h2 { margin-top: 0; }
    </style>
</head>
<body>
    <div class='error'>
        <h2>❌ Error al crear la base de datos</h2>
        <p>" . $conn->error . "</p>
    </div>
</body>
</html>";
}

$conn->close();
?>
