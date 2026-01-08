<?php
// Run this once (open in browser) to create default admin user if not exists.
// After creating, delete or restrict access to this file.

header('Content-Type: text/plain; charset=utf-8');

$host = '127.0.0.1';
$db   = 'gis_jambu';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    http_response_code(500);
    echo "DB connection failed: " . $e->getMessage();
    exit;
}

$username = 'admin';
$passwordPlain = 'admin'; // change after first login

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    echo "User 'admin' already exists.\n";
    exit;
}

$hash = password_hash($passwordPlain, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
$stmt->execute([$username, $hash, 'admin']);

echo "Created user 'admin' with password 'admin'. Please change password after first login and delete this file.\n";
