<?php

require_once "../config/database.php";
require_once "../includes/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    response(false, "Nem megfelelo keres!");
}

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"] ?? "");
$firstname = trim($data["firstname"] ?? "");
$lastname = trim($data["lastname"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if (!$username || !$email || !$password) {
    response(false, "Minden kotelezo mezot tolts ki!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    response(false, "Ervenytelen email cim!");
}

if (strlen($password) < 8) {
    response(false, "A jelszo legalabb 8 karakter legyen!");
}

$check = $conn->prepare(
    "SELECT id FROM users WHERE username = ? OR email = ?"
);
$check->execute([$username, $email]);

if ($check->fetch()) {
    response(false, "Ez a felhasznalonev vagy email mar foglalt!");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users
(username, firstname, lastname, email, password)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    $username,
    $firstname,
    $lastname,
    $email,
    $hashedPassword
]);

response(true, "Sikeres regisztracio!");

?>
