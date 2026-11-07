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

if (!$data || empty($data["id"])) {
    http_response_code(400);
    response(false, "Hiányzó járműazonosító!");
    exit;
}

$id = filter_var($data["id"], FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    response(false, "Érvénytelen járműazonosító!");
    exit;
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
    exit;
}

if (isset($data["evjarat"]) &&
    (!filter_var($data["evjarat"], FILTER_VALIDATE_INT) ||
    $data["evjarat"] < 1900 ||
    $data["evjarat"] > (int)date("Y") + 1)) {
    http_response_code(400);
    response(false, "Érvénytelen évjárat!");
    exit;
}

if (isset($data["ar"]) &&
    (!filter_var($data["ar"], FILTER_VALIDATE_INT) ||
    $data["ar"] < 0)) {
    http_response_code(400);
    response(false, "Érvénytelen ár!");
    exit;
}

try {
    $ellenorzes = $conn->prepare(
        "SELECT id FROM jarmuvek
         WHERE id = :id AND tulaj_id = :tulaj_id"
    );

    $ellenorzes->execute([
        ":id" => $id,
        ":tulaj_id" => $_SESSION["user_id"]
    ]);

    if (!$ellenorzes->fetch()) {
        http_response_code(404);
        response(false, "A hirdetés nem található, vagy nem a tiéd!");
        exit;
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
