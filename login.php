<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    response(false, "Nem megfelelő kérés!");
}

session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    response(false, "Hibás adatok!");
}

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($email === "" || $password === "") {
    http_response_code(400);
    response(false, "Minden mező kitöltése kötelező!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    response(false, "Érvénytelen email cím!");
}

try {
    $stmt = $conn->prepare(
        "SELECT id, username, email, password, szerepkor
         FROM users
         WHERE email = ?"
    );

    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user["password"])) {
        http_response_code(401);
        response(false, "Hibás email vagy jelszó!");
    }

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["szerepkor"] = $user["szerepkor"];

    unset($user["password"]);

    response(true, "Sikeres bejelentkezés!", $user);

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Adatbázis hiba!");
}

?>
