<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/auth_check.php";

requireLogin();


if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    response(false, "Először jelentkezz be!");
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    response(false, "Hibás adatok!");
    exit;
}

$marka = trim($data["marka"] ?? "");
$modell = trim($data["modell"] ?? "");
$tipus = trim($data["tipus"] ?? "");
$evjarat = $data["evjarat"] ?? null;
$ar = $data["ar"] ?? null;

$uzemanyag = $data["uzemanyag"] ?? null;
$futott_km = $data["futott_km"] ?? null;
$helyseg = $data["helyseg"] ?? null;
$kep_url = $data["kep_url"] ?? null;

if ($marka === "" || $modell === "" || $tipus === "" ||
    !filter_var($evjarat, FILTER_VALIDATE_INT) ||
    !filter_var($ar, FILTER_VALIDATE_INT) ||
    $evjarat < 1900 || $evjarat > (int)date("Y") + 1 ||
    $ar < 0) {
    http_response_code(400);
    response(false, "Ellenőrizd a kötelező adatokat!");
    exit;
}

try {
    $sql = "INSERT INTO jarmuvek
            (marka, modell, tipus, evjarat, ar, uzemanyag,
             futott_km, helyseg, kep_url, tulaj_id, allapot)
            VALUES
            (:marka, :modell, :tipus, :evjarat, :ar, :uzemanyag,
             :futott_km, :helyseg, :kep_url, :tulaj_id, 'aktiv')";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":marka" => $marka,
        ":modell" => $modell,
        ":tipus" => $tipus,
        ":evjarat" => $evjarat,
        ":ar" => $ar,
        ":uzemanyag" => $uzemanyag,
        ":futott_km" => $futott_km,
        ":helyseg" => $helyseg,
        ":kep_url" => $kep_url,
        ":tulaj_id" => $_SESSION["user_id"]
    ]);

    response(true, "Sikeres hirdetésfeladás!", [
        "id" => $conn->lastInsertId()
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Hiba történt a hirdetés mentésekor!");
}

?>
