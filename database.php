<?php

$user = "postgres";
$pass = "abcd1234";
$conn = new PDO("pgsql:host=localhost;port=5432;dbname=tes_sitio", $user, $pass);
