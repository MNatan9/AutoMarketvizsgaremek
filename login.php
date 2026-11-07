<?php

require_once "../config/database.php";
require_once "../includes/response.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    response(false, "Nem megfelelo keres!");
}

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

$stmt = $conn->prepare(
    "SELECT id, username, email, password, szerepkor
    FROM users WHERE email = ?"
);

$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password"])) {
    http_response_code(401);
    response(false, "Hibas email vagy jelszo!");
}

session_regenerate_id(true);

$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["szerepkor"] = $user["szerepkor"];

unset($user["password"]);

response(true, "Sikeres bejelentkezes!", $user);

?>
