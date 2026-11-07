<?php

require_once "../includes/response.php";
session_start();
$_SESSION = [];
session_destroy();
response(true, "Sikeres kijelentkezes!");

?>
