-- Crear base de datos
CREATE DATABASE IF NOT EXISTS portfolio_contact;

-- Usar la base de datos
USE portfolio_contact;

-- Crear tabla de mensajes
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

-- (Opcional) Crear tabla para logs de envíos fallidos
CREATE TABLE IF NOT EXISTS contact_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT,
    status VARCHAR(50),
    error_message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    INDEX idx_message_id (message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
