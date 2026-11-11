<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/auth_check.php";

requireLogin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    response(false, "Nem megfelelő kérés!");
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    response(false, "Hibás adatok!");
}

$id = filter_var($data["id"] ?? null, FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    response(false, "Érvénytelen járműazonosító!");
}

try {

    $stmt = $conn->prepare(
        "SELECT id, tulaj_id FROM jarmuvek WHERE id = ?"
    );

    $stmt->execute([$id]);
    $jarmu = $stmt->fetch();

    if (!$jarmu) {
        http_response_code(404);
        response(false, "A jármű nem található!");
    }

    $isAdmin = ($_SESSION["szerepkor"] ?? "") === "admin";
    $isOwner = (int)$jarmu["tulaj_id"] === (int)$_SESSION["user_id"];

    if (!$isAdmin && !$isOwner) {
        http_response_code(403);
        response(false, "Nincs jogosultságod a törléshez!");
    }

    $stmt = $conn->prepare(
        "DELETE FROM jarmuvek WHERE id = ?"
    );

    $stmt->execute([$id]);

    response(true, "Sikeres járműtörlés!");

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Adatbázis hiba!");
}

?>
