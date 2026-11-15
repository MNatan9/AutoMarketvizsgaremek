<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../includes/auth_check.php";

requireLogin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    response(false, "Nem megfelelő kérés!");
}

if (!isset($_FILES["kep"])) {
    http_response_code(400);
    response(false, "Nem érkezett kép!");
}

$file = $_FILES["kep"];

if ($file["error"] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    response(false, "Hiba történt a feltöltés során!");
}

$maxSize = 5 * 1024 * 1024;

if ($file["size"] > $maxSize) {
    http_response_code(400);
    response(false, "A kép maximum 5 MB lehet!");
}

$imageInfo = getimagesize($file["tmp_name"]);

if ($imageInfo === false) {
    http_response_code(400);
    response(false, "A feltöltött fájl nem kép!");
}

$mime = $imageInfo["mime"];

$allowedTypes = [
    "image/jpeg" => "jpg",
    "image/png" => "png",
    "image/webp" => "webp"
];

if (!isset($allowedTypes[$mime])) {
    http_response_code(400);
    response(false, "Csak JPG, PNG és WEBP képek tölthetők fel!");
}

$uploadDir = __DIR__ . "/../uploads/vehicles/";

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        response(false, "A feltöltési mappa nem hozható létre!");
    }
}

$fileName = bin2hex(random_bytes(16)) . "." . $allowedTypes[$mime];

$filePath = $uploadDir . $fileName;

if (!move_uploaded_file($file["tmp_name"], $filePath)) {
    http_response_code(500);
    response(false, "A kép mentése sikertelen!");
}

$imageUrl = "uploads/vehicles/" . $fileName;

response(true, "Sikeres képfeltöltés!", [
    "kep_url" => $imageUrl
]);

?>