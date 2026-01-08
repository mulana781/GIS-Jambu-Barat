<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/db.php';

try {
    // Ambil polygon terbaru
    $stmt = $pdo->query('SELECT geojson FROM boundaries ORDER BY id DESC LIMIT 1');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || empty($row['geojson'])) {
        http_response_code(404);
        echo json_encode(['error' => 'Boundary not found']);
        exit;
    }

    // Pastikan geojson valid; jika string, kirim langsung
    $decoded = json_decode($row['geojson'], true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($decoded);
    } else {
        // Bila sudah berbentuk array JSON di DB, tetap kirim raw
        echo $row['geojson'];
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch boundary']);
}

