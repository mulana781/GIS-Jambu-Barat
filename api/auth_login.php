<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$host = '127.0.0.1';
$db   = 'gis_jambu';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing username or password']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userRow || !password_verify($password, $userRow['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
}

// Set session
$_SESSION['user'] = ['id' => $userRow['id'], 'username' => $userRow['username'], 'role' => $userRow['role']];

echo json_encode(['ok' => true, 'user' => $_SESSION['user']]);
