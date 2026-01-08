<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/db.php';

try {
    $stmt = $pdo->query('SELECT id, name, latitude, longitude, description FROM markers ORDER BY id DESC');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch markers']);
}

