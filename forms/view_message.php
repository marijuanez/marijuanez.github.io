<?php
// Ver detalle de un mensaje
// Acceso: http://localhost:8888/Portfolio/forms/view_message.php?id=1

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "portfolio_contact";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Obtener ID del mensaje
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    header("Location: view_messages.php");
    exit;
}

// Obtener mensaje
$sql = "SELECT * FROM messages WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Mensaje no encontrado");
}

$message = $result->fetch_assoc();

// Marcar como leído
if ($message["read_status"] == 0) {
    $update_sql = "UPDATE messages SET read_status = 1 WHERE id = $id";
    $conn->query($update_sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($message["subject"]); ?> - Mensaje</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: #667eea;
            color: white;
            padding: 30px;
        }
        
        .header h1 {
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .meta {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            flex-wrap: wrap;
            font-size: 14px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .meta-label {
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .message-body {
            background: #f9f9f9;
            padding: 20px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .footer {
            padding: 20px 30px;
            background: #f5f5f5;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #764ba2;
        }
        
        .btn-secondary {
            background: #999;
        }
        
        .btn-secondary:hover {
            background: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($message["subject"]); ?></h1>
            <div class="meta">
                <div class="meta-item">
                    <span class="meta-label">De:</span>
                    <span><?php echo htmlspecialchars($message["name"]); ?> (<?php echo htmlspecialchars($message["email"]); ?>)</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Fecha:</span>
                    <span><?php echo date("d/m/Y H:i", strtotime($message["created_at"])); ?></span>
                </div>
            </div>
        </div>
        
        <div class="content">
            <h3>Mensaje:</h3>
            <div class="message-body">
                <?php echo htmlspecialchars($message["message"]); ?>
            </div>
            
            <h3>Información del contacto:</h3>
            <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($message["email"]); ?>"><?php echo htmlspecialchars($message["email"]); ?></a></p>
            <p><strong>IP:</strong> <?php echo htmlspecialchars($message["ip_address"]); ?></p>
            <p><strong>Recibido:</strong> <?php echo date("d/m/Y H:i:s", strtotime($message["created_at"])); ?></p>
        </div>
        
        <div class="footer">
            <a href="mailto:<?php echo htmlspecialchars($message["email"]); ?>" class="btn">Responder por Email</a>
            <a href="view_messages.php" class="btn btn-secondary">Volver a la lista</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
