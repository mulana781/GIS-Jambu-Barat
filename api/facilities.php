<?php
// Simple REST API for fasilitas (PHP + PDO)
// Place this file in c:\laragon\www\GIS\api\facilities.php

header('Content-Type: application/json; charset=utf-8');

$host = '127.0.0.1';
$db   = 'gis_jambu';
$user = 'root';
$pass = ''; // Laragon default biasanya kosong
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed', 'message' => $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Optional: ?id=1 to get single
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM fasilitas WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row ?: []);
        exit;
    }

    $stmt = $pdo->query('SELECT * FROM fasilitas ORDER BY id DESC');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    // create
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO fasilitas (name, category, lat, lng, description) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $input['name'] ?? '',
        $input['category'] ?? '',
        isset($input['lat']) ? $input['lat'] : 0,
        isset($input['lng']) ? $input['lng'] : 0,
        $input['description'] ?? ''
    ]);

    echo json_encode(['id' => $pdo->lastInsertId()]);
    exit;
}

if ($method === 'PUT') {
    // update ?id=1
    $id = $_GET['id'] ?? null;
    if (!$id || !$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id or invalid input']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE fasilitas SET name=?, category=?, lat=?, lng=?, description=? WHERE id=?');
    $stmt->execute([
        $input['name'] ?? '',
        $input['category'] ?? '',
        isset($input['lat']) ? $input['lat'] : 0,
        isset($input['lng']) ? $input['lng'] : 0,
        $input['description'] ?? '',
        $id
    ]);

    echo json_encode(['ok' => true]);
    exit;
}

if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM fasilitas WHERE id = ?');
    $stmt->execute([$id]);

    echo json_encode(['ok' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
