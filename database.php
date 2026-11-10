<?php

$user = "postgres";
$pass = "abcd1234";
$dbname = "tes_sitio";
$conn = new PDO(
    "pgsql:host=localhost;port=5432;dbname=$dbname",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => true,
    ],
);
