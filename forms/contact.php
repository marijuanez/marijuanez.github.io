<?php
// Configuración de base de datos
$servername = "localhost";
$username = "root";
$password = "root"; // MAMP por defecto usa "root"
$dbname = "portfolio_contact";
$port = 3306; // MAMP por defecto usa 3306

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Establecer charset a UTF-8
$conn->set_charset("utf8mb4");

// Validar que sea una solicitud POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die(json_encode(["error" => "Method not allowed"]));
}

// Obtener datos del formulario
$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
$message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

// Validar campos requeridos
$errors = [];

if (empty($name) || strlen($name) < 4) {
    $errors[] = "El nombre debe tener al menos 4 caracteres.";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Por favor, ingresa un email válido.";
}

if (empty($subject) || strlen($subject) < 4) {
    $errors[] = "El asunto debe tener al menos 4 caracteres.";
}

if (empty($message)) {
    $errors[] = "El mensaje no puede estar vacío.";
}

// Si hay errores, retornarlos
if (!empty($errors)) {
    http_response_code(400);
    die(json_encode(["error" => implode(" ", $errors)]));
}

// Sanitizar datos para insertar en BD
$name = $conn->real_escape_string($name);
$email = $conn->real_escape_string($email);
$subject = $conn->real_escape_string($subject);
$message = $conn->real_escape_string($message);

// Insertar en base de datos
$ip_address = $_SERVER["REMOTE_ADDR"];
$user_agent = $_SERVER["HTTP_USER_AGENT"];
$created_at = date("Y-m-d H:i:s");

$sql = "INSERT INTO messages (name, email, subject, message, ip_address, user_agent, created_at, read_status) 
        VALUES ('$name', '$email', '$subject', '$message', '$ip_address', '$user_agent', '$created_at', 0)";

if ($conn->query($sql) === TRUE) {
    // Mensaje guardado exitosamente
    
    // (Opcional) Enviar email de confirmación al cliente
    $to = $email;
    $email_subject = "Recibimos tu mensaje - Portfolio Oskar Marijuan";
    $email_body = "Hola $name,\n\nGracias por ponerte en contacto conmigo.\nHe recibido tu mensaje y me pondré en contacto contigo lo antes posible.\n\nAsunto: $subject\n\nSaludos cordiales,\nOskar Marijuan";
    $headers = "From: oskar.marijuan@gmail.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Intentar enviar email (opcional)
    @mail($to, $email_subject, $email_body, $headers);
    
    // Respuesta de éxito
    http_response_code(200);
    echo json_encode(["success" => "Mensaje enviado correctamente. Me pondré en contacto pronto."]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al guardar el mensaje: " . $conn->error]);
}

$conn->close();
?>
