<?php

session_start();

require_once __DIR__ . "/response.php";

function requireLogin() {
    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        response(false, "Bejelentkezes szukseges!");
    }
}

function requireAdmin() {
    requireLogin();

    if ($_SESSION["szerepkor"] !== "admin") {
        http_response_code(403);
        response(false, "Nincs jogosultsagod!");
    }
}

?>
