<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/response.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    response(false, "Nem megfelelő kérés!");
}

try {

    $stmt = $conn->prepare(
        "SELECT id, marka, modell, tipus, evjarat, ar,
                uzemanyag, futott_km, helyseg, kep_url,
                tulaj_id, feltoltes_datum
         FROM jarmuvek
         WHERE allapot = 'aktiv'
         ORDER BY feltoltes_datum DESC"
    );

    $stmt->execute();

    $jarmuvek = $stmt->fetchAll();

    response(true, "Sikeres lekérés!", $jarmuvek);

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Adatbázis hiba!");
}

?>
