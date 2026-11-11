<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    response(false, "Nem megfelelő kérés!");
}

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    response(false, "Érvénytelen járműazonosító!");
}

try {

    $stmt = $conn->prepare(
        "SELECT id, marka, modell, tipus, evjarat, ar,
                uzemanyag, futott_km, helyseg, kep_url,
                tulaj_id, allapot, feltoltes_datum
         FROM jarmuvek
         WHERE id = ?"
    );

    $stmt->execute([$id]);
    $jarmu = $stmt->fetch();

    if (!$jarmu) {
        http_response_code(404);
        response(false, "A jármű nem található!");
    }

    response(true, "Sikeres lekérés!", $jarmu);

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Adatbázis hiba!");
}

?>
