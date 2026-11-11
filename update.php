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

$mezok = [
    "marka",
    "modell",
    "tipus",
    "evjarat",
    "ar",
    "uzemanyag",
    "futott_km",
    "helyseg",
    "kep_url"
];

$frissitesek = [];
$ertekek = [];

foreach ($mezok as $mezo) {
    if (array_key_exists($mezo, $data)) {
        $frissitesek[] = "$mezo = :$mezo";
        $ertekek[":$mezo"] = $data[$mezo];
    }
}

if (empty($frissitesek)) {
    http_response_code(400);
    response(false, "Nincs módosítandó adat!");
}

if (isset($data["marka"]) &&
    (!is_string($data["marka"]) || trim($data["marka"]) === "" || strlen($data["marka"]) > 50)) {
    http_response_code(400);
    response(false, "Érvénytelen márka!");
}

if (isset($data["modell"]) &&
    (!is_string($data["modell"]) || trim($data["modell"]) === "" || strlen($data["modell"]) > 50)) {
    http_response_code(400);
    response(false, "Érvénytelen modell!");
}

if (isset($data["tipus"]) &&
    (!is_string($data["tipus"]) || trim($data["tipus"]) === "" || strlen($data["tipus"]) > 20)) {
    http_response_code(400);
    response(false, "Érvénytelen típus!");
}

if (isset($data["evjarat"]) &&
    (!filter_var($data["evjarat"], FILTER_VALIDATE_INT) ||
    $data["evjarat"] < 1900 ||
    $data["evjarat"] > (int)date("Y") + 1)) {
    http_response_code(400);
    response(false, "Érvénytelen évjárat!");
}

if (isset($data["ar"]) &&
    (!filter_var($data["ar"], FILTER_VALIDATE_INT) ||
    $data["ar"] < 0)) {
    http_response_code(400);
    response(false, "Érvénytelen ár!");
}

if (isset($data["futott_km"]) &&
    $data["futott_km"] !== null &&
    (!filter_var($data["futott_km"], FILTER_VALIDATE_INT) ||
    $data["futott_km"] < 0)) {
    http_response_code(400);
    response(false, "Érvénytelen kilométeróra-állás!");
}

if (isset($data["uzemanyag"]) &&
    (!is_string($data["uzemanyag"]) || strlen($data["uzemanyag"]) > 30)) {
    http_response_code(400);
    response(false, "Érvénytelen üzemanyag!");
}

if (isset($data["helyseg"]) &&
    (!is_string($data["helyseg"]) || strlen($data["helyseg"]) > 50)) {
    http_response_code(400);
    response(false, "Érvénytelen helység!");
}

if (isset($data["kep_url"]) &&
    (!is_string($data["kep_url"]) || strlen($data["kep_url"]) > 255)) {
    http_response_code(400);
    response(false, "Érvénytelen kép URL!");
}

try {

    $isAdmin = ($_SESSION["szerepkor"] ?? "") === "admin";

    if ($isAdmin) {
        $ellenorzes = $conn->prepare(
            "SELECT id FROM jarmuvek WHERE id = ?"
        );

        $ellenorzes->execute([$id]);

    } else {
        $ellenorzes = $conn->prepare(
            "SELECT id FROM jarmuvek
             WHERE id = ? AND tulaj_id = ?"
        );

        $ellenorzes->execute([
            $id,
            $_SESSION["user_id"]
        ]);
    }

    if (!$ellenorzes->fetch()) {
        http_response_code(404);
        response(false, "A hirdetés nem található, vagy nincs jogosultságod!");
    }

    $ertekek[":id"] = $id;

    $sql = "UPDATE jarmuvek SET " .
           implode(", ", $frissitesek) .
           " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute($ertekek);

    response(true, "A hirdetés sikeresen módosult!");

} catch (PDOException $e) {
    http_response_code(500);
    response(false, "Hiba történt a módosítás során!");
}

?>
