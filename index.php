<?php

define("BASE_URL", "/SiTIO");

require_once __DIR__ . "/database.php";

$method = $_SERVER["REQUEST_METHOD"];
$page = trim((string) ($_GET["page"] ?? ""), "/");
$route = "/" . $page;
unset($_GET["page"]); // remove unused

$routes = [
    "/" => [
        "GET" => BASE_URL . "/pages/user/login_get.php",
    ],
    "/login" => [
        "GET" => BASE_URL . "/pages/user/login_get.php",
        "POST" => BASE_URL . "/pages/user/login_post.php",
    ],
    "/logout" => [
        "POST" => BASE_URL . "/pages/user/logout_post.php",
    ],
    "/departments" => [
        "GET" => BASE_URL . "/pages/department/dashboard_get.php",
    ],
    "/departments/create" => [
        "GET" => BASE_URL . "/pages/department/create_get.php",
        "POST" => BASE_URL . "/pages/department/create_post.php",
    ],
    "/departments/edit" => [
        "GET" => BASE_URL . "/pages/department/edit_get.php",
        "POST" => BASE_URL . "/pages/department/edit_post.php",
    ],
    "/departments/delete" => [
        "POST" => BASE_URL . "/pages/department/delete_post.php",
    ],
    "/study-programs" => [
        "GET" => BASE_URL . "/pages/studyprogram/dashboard_get.php",
    ],
    "/study-programs/create" => [
        "GET" => BASE_URL . "/pages/studyprogram/create_get.php",
        "POST" => BASE_URL . "/pages/studyprogram/create_post.php",
    ],
    "/study-programs/edit" => [
        "GET" => BASE_URL . "/pages/studyprogram/edit_get.php",
        "POST" => BASE_URL . "/pages/studyprogram/edit_post.php",
    ],
    "/study-programs/delete" => [
        "POST" => BASE_URL . "/pages/studyprogram/delete_post.php",
    ],
    "/faq" => [
        "GET" => BASE_URL . "/pages/faq/dashboard_get.php",
    ],
    "/faq/create" => [
        "GET" => BASE_URL . "/pages/faq/create_get.php",
        "POST" => BASE_URL . "/pages/faq/create_post.php",
    ],
    "/faq/edit" => [
        "GET" => BASE_URL . "/pages/faq/edit_get.php",
        "POST" => BASE_URL . "/pages/faq/edit_post.php",
    ],
    "/faq/delete" => [
        "POST" => BASE_URL . "/pages/faq/delete_post.php",
    ],
    "/faqs" => [
        "GET" => BASE_URL . "/pages/faq/dashboard_get.php",
    ],
    "/faqs/create" => [
        "GET" => BASE_URL . "/pages/faq/create_get.php",
        "POST" => BASE_URL . "/pages/faq/create_post.php",
    ],
    "/faqs/edit" => [
        "GET" => BASE_URL . "/pages/faq/edit_get.php",
        "POST" => BASE_URL . "/pages/faq/edit_post.php",
    ],
    "/faqs/delete" => [
        "POST" => BASE_URL . "/pages/faq/delete_post.php",
    ],
    "/programs" => [
        "GET" => BASE_URL . "/pages/program/dashboard_get.php",
    ],
    "/programs/create" => [
        "GET" => BASE_URL . "/pages/program/create_get.php",
        "POST" => BASE_URL . "/pages/program/create_post.php",
    ],
    "/programs/edit" => [
        "GET" => BASE_URL . "/pages/program/edit_get.php",
        "POST" => BASE_URL . "/pages/program/edit_post.php",
    ],
    "/programs/delete" => [
        "POST" => BASE_URL . "/pages/program/delete_post.php",
    ],
    "/document-types" => [
        "GET" => BASE_URL . "/pages/documenttype/dashboard_get.php",
    ],
    "/document-types/create" => [
        "GET" => BASE_URL . "/pages/documenttype/create_get.php",
        "POST" => BASE_URL . "/pages/documenttype/create_post.php",
    ],
    "/document-types/edit" => [
        "GET" => BASE_URL . "/pages/documenttype/edit_get.php",
        "POST" => BASE_URL . "/pages/documenttype/edit_post.php",
    ],
    "/document-types/delete" => [
        "POST" => BASE_URL . "/pages/documenttype/delete_post.php",
    ],
    "/tickets" => [
        "GET" => BASE_URL . "/pages/ticket/dashboard_get.php",
    ],
    "/tickets/create" => [
        "GET" => BASE_URL . "/pages/ticket/create_get.php",
        "POST" => BASE_URL . "/pages/ticket/create_post.php",
    ],
    "/tickets/edit" => [
        "GET" => BASE_URL . "/pages/ticket/edit_get.php",
        "POST" => BASE_URL . "/pages/ticket/edit_post.php",
    ],
    "/tickets/delete" => [
        "POST" => BASE_URL . "/pages/ticket/delete_post.php",
    ],
    "/ticket-media" => [
        "GET" => BASE_URL . "/pages/ticketmedia/dashboard_get.php",
    ],
    "/ticket-media/create" => [
        "GET" => BASE_URL . "/pages/ticketmedia/create_get.php",
        "POST" => BASE_URL . "/pages/ticketmedia/create_post.php",
    ],
    "/ticket-media/edit" => [
        "GET" => BASE_URL . "/pages/ticketmedia/edit_get.php",
        "POST" => BASE_URL . "/pages/ticketmedia/edit_post.php",
    ],
    "/ticket-media/delete" => [
        "POST" => BASE_URL . "/pages/ticketmedia/delete_post.php",
    ],
    "/users" => [
        "GET" => BASE_URL . "/pages/user/dashboard_get.php",
    ],
    "/users/create" => [
        "GET" => BASE_URL . "/pages/user/create_get.php",
        "POST" => BASE_URL . "/pages/user/create_post.php",
    ],
    "/users/edit" => [
        "GET" => BASE_URL . "/pages/user/edit_get.php",
        "POST" => BASE_URL . "/pages/user/edit_post.php",
    ],
    "/users/delete" => [
        "POST" => BASE_URL . "/pages/user/delete_post.php",
    ],
    "/user-legal" => [
        "GET" => BASE_URL . "/pages/userlegal/dashboard_get.php",
    ],
    "/user-legal/edit" => [
        "GET" => BASE_URL . "/pages/userlegal/edit_get.php",
        "POST" => BASE_URL . "/pages/userlegal/edit_post.php",
    ],
];

$matchedRoute = $routes[$route] ?? null;

if ($matchedRoute === null) {
    http_response_code(404);
    exit();
}

if (!isset($matchedRoute[$method])) {
    http_response_code(405);
    exit();
}

require __DIR__ . "/" . ltrim($matchedRoute[$method], BASE_URL);
