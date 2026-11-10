<?php

$method = $_SERVER["REQUEST_METHOD"];
if(!isset($_GET['page'])) {
    $route = "/";
} else {
    $route = "/{$_GET['page']}";
    unset($_GET['page']); // remove unused
}

var_dump($method, $route);
