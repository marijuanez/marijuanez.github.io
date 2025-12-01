<?php
// Panel simple para ver mensajes recibidos
// Acceso: http://localhost:8888/Portfolio/forms/view_messages.php

// Configuración de base de datos
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

// Obtener todos los mensajes
$sql = "SELECT id, name, email, subject, created_at, read_status FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes de Contacto - Portfolio</title>
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
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .unread {
            background: #fff3cd;
            font-weight: 600;
        }
        
        .read {
            background: #e8f5e9;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-unread {
            background: #ff9800;
            color: white;
        }
        
        .badge-read {
            background: #4caf50;
            color: white;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
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
        
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Mensajes de Contacto Recibidos</h1>
        
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Email</th>";
            echo "<th>Asunto</th>";
            echo "<th>Fecha</th>";
            echo "<th>Estado</th>";
            echo "<th>Acción</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            while($row = $result->fetch_assoc()) {
                $read_class = $row["read_status"] == 0 ? "unread" : "read";
                $badge_class = $row["read_status"] == 0 ? "badge-unread" : "badge-read";
                $badge_text = $row["read_status"] == 0 ? "NO LEÍDO" : "LEÍDO";
                
                echo "<tr class='$read_class'>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["subject"]) . "</td>";
                echo "<td>" . date("d/m/Y H:i", strtotime($row["created_at"])) . "</td>";
                echo "<td><span class='badge $badge_class'>$badge_text</span></td>";
                echo "<td><a href='view_message.php?id=" . $row["id"] . "' class='btn btn-small'>Ver</a></td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div class='empty'><p>No hay mensajes recibidos aún.</p></div>";
        }
        ?>
        
        <div class="footer">
            <p>Total de mensajes: <?php echo $result->num_rows; ?></p>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
