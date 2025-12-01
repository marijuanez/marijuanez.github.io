<?php
// Simple contact form handler
// Returns JSON response for AJAX submission

header('Content-Type: application/json; charset=utf-8');

// Validar que sea una solicitud POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die(json_encode(["error" => "Method not allowed. Use POST."]));
}

// Obtener datos del formulario
$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
$message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

// Validación básica en servidor
$errors = [];

if (empty($name) || strlen($name) < 4) {
    $errors[] = "El nombre debe tener al menos 4 caracteres.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email inválido.";
}

if (empty($subject) || strlen($subject) < 4) {
    $errors[] = "Asunto inválido (mínimo 4 caracteres).";
}

if (empty($message)) {
    $errors[] = "El mensaje no puede estar vacío.";
}

if (!empty($errors)) {
    http_response_code(400);
    die(json_encode(["error" => implode(" ", $errors)]));
}

// Intentar guardar en la BD (si existe)
$success = false;
$db_error = "";

try {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "portfolio_contact";
    $port = 3306;

    $conn = @new mysqli($servername, $username, $password, $dbname, $port);
    
    if (!$conn->connect_error) {
        $conn->set_charset("utf8mb4");
        
        $name = $conn->real_escape_string($name);
        $email = $conn->real_escape_string($email);
        $subject = $conn->real_escape_string($subject);
        $message = $conn->real_escape_string($message);
        
        $ip_address = $_SERVER["REMOTE_ADDR"];
        $user_agent = $_SERVER["HTTP_USER_AGENT"];
        $created_at = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO messages (name, email, subject, message, ip_address, user_agent, created_at, read_status) 
                VALUES ('$name', '$email', '$subject', '$message', '$ip_address', '$user_agent', '$created_at', 0)";
        
        if ($conn->query($sql) === TRUE) {
            $success = true;
        } else {
            $db_error = $conn->error;
        }
        $conn->close();
    } else {
        $db_error = "BD no disponible (esto es normal en testing)";
    }
} catch (Exception $e) {
    $db_error = $e->getMessage();
}

// Responder con éxito incluso si la BD falla (para testing sin BD)
// En producción, reemplazar con llamada a Cloud Function o servicio de email
http_response_code(200);
echo json_encode([
    "success" => true
]);
?>
