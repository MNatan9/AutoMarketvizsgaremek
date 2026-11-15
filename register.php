<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    response(false, "Nem megfelelő kérés!");
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    response(false, "Hibás adatok!");
}

$username = trim($data["username"] ?? "");
$firstname = trim($data["firstname"] ?? "");
$lastname = trim($data["lastname"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";

if ($username === "" || $email === "" || $password === "") {
    http_response_code(400);
    response(false, "Minden kötelező mezőt tölts ki!");
}

if (strlen($username) > 50 ||
    strlen($firstname) > 50 ||
    strlen($lastname) > 50 ||
    strlen($email) > 100) {
    http_response_code(400);
    response(false, "Túl hosszú adat!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    response(false, "Érvénytelen email cím!");
}

if (strlen($password) < 8) {
    http_response_code(400);
    response(false, "A jelszó legalább 8 karakter legyen!");
}

try {

    $check = $conn->prepare(
        "SELECT id FROM users WHERE username = ? OR email = ?"
    );

    $check->execute([$username, $email]);

    if ($check->fetch()) {
        http_response_code(409);
        response(false, "Ez a felhasználónév vagy email már foglalt!");
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

    http_response_code(201);
    response(true, "Sikeres regisztráció!");

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Adatbázis hiba!");
}

?>
