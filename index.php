<?php

require_once __DIR__ . "/database.php";

$method = $_SERVER["REQUEST_METHOD"];
$page = trim((string) ($_GET["page"] ?? ""), "/");
$route = "/" . $page;
unset($_GET["page"]); // remove unused

$base_url = dirname($_SERVER["SCRIPT_FILENAME"]);
$base_url = str_replace($_SERVER["DOCUMENT_ROOT"], "", $base_url);
define("BASE_URL", $base_url);

$routes = [
    "/" => [
        "GET" => "pages/user/login_get.php",
    ],
    "/login" => [
        "GET" => "pages/user/login_get.php",
        "POST" => "pages/user/login_post.php",
    ],
    "/logout" => [
        "POST" => "pages/user/logout_post.php",
    ],
    "/departments" => [
        "GET" => "pages/department/dashboard_get.php",
    ],
    "/departments/create" => [
        "GET" => "pages/department/create_get.php",
        "POST" => "pages/department/create_post.php",
    ],
    "/departments/edit" => [
        "GET" => "pages/department/edit_get.php",
        "POST" => "pages/department/edit_post.php",
    ],
    "/departments/delete" => [
        "POST" => "pages/department/delete_post.php",
    ],
    "/study-programs" => [
        "GET" => "pages/studyprogram/dashboard_get.php",
    ],
    "/study-programs/create" => [
        "GET" => "pages/studyprogram/create_get.php",
        "POST" => "pages/studyprogram/create_post.php",
    ],
    "/study-programs/edit" => [
        "GET" => "pages/studyprogram/edit_get.php",
        "POST" => "pages/studyprogram/edit_post.php",
    ],
    "/study-programs/delete" => [
        "POST" => "pages/studyprogram/delete_post.php",
    ],
    "/faqs" => [
        "GET" => "pages/faq/dashboard_get.php",
    ],
    "/faqs/create" => [
        "GET" => "pages/faq/create_get.php",
        "POST" => "pages/faq/create_post.php",
    ],
    "/faqs/edit" => [
        "GET" => "pages/faq/edit_get.php",
        "POST" => "pages/faq/edit_post.php",
    ],
    "/faqs/delete" => [
        "POST" => "pages/faq/delete_post.php",
    ],
    "/programs" => [
        "GET" => "pages/program/dashboard_get.php",
    ],
    "/programs/create" => [
        "GET" => "pages/program/create_get.php",
        "POST" => "pages/program/create_post.php",
    ],
    "/programs/edit" => [
        "GET" => "pages/program/edit_get.php",
        "POST" => "pages/program/edit_post.php",
    ],
    "/programs/delete" => [
        "POST" => "pages/program/delete_post.php",
    ],
    "/document-types" => [
        "GET" => "pages/documenttype/dashboard_get.php",
    ],
    "/document-types/create" => [
        "GET" => "pages/documenttype/create_get.php",
        "POST" => "pages/documenttype/create_post.php",
    ],
    "/document-types/edit" => [
        "GET" => "pages/documenttype/edit_get.php",
        "POST" => "pages/documenttype/edit_post.php",
    ],
    "/document-types/delete" => [
        "POST" => "pages/documenttype/delete_post.php",
    ],
    "/tickets" => [
        "GET" => "pages/ticket/dashboard_get.php",
    ],
    "/tickets/create" => [
        "GET" => "pages/ticket/create_get.php",
        "POST" => "pages/ticket/create_post.php",
    ],
    "/tickets/edit" => [
        "GET" => "pages/ticket/edit_get.php",
        "POST" => "pages/ticket/edit_post.php",
    ],
    "/tickets/delete" => [
        "POST" => "pages/ticket/delete_post.php",
    ],
    "/ticket-media" => [
        "GET" => "pages/ticketmedia/dashboard_get.php",
    ],
    "/ticket-media/create" => [
        "GET" => "pages/ticketmedia/create_get.php",
        "POST" => "pages/ticketmedia/create_post.php",
    ],
    "/ticket-media/edit" => [
        "GET" => "pages/ticketmedia/edit_get.php",
        "POST" => "pages/ticketmedia/edit_post.php",
    ],
    "/ticket-media/delete" => [
        "POST" => "pages/ticketmedia/delete_post.php",
    ],
    "/users" => [
        "GET" => "pages/user/dashboard_get.php",
    ],
    "/users/create" => [
        "GET" => "pages/user/create_get.php",
        "POST" => "pages/user/create_post.php",
    ],
    "/users/edit" => [
        "GET" => "pages/user/edit_get.php",
        "POST" => "pages/user/edit_post.php",
    ],
    "/users/delete" => [
        "POST" => "pages/user/delete_post.php",
    ],
    "/user-legal" => [
        "GET" => "pages/userlegal/dashboard_get.php",
    ],
    "/user-legal/edit" => [
        "GET" => "pages/userlegal/edit_get.php",
        "POST" => "pages/userlegal/edit_post.php",
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

require __DIR__ . "/" . $matchedRoute[$method];
