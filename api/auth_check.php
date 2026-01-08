<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!empty($_SESSION['user'])) {
    echo json_encode(['logged' => true, 'user' => $_SESSION['user']]);
} else {
    http_response_code(401);
    echo json_encode(['logged' => false]);
}
