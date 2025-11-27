<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$data_file = 'clicks.json';
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['userId'] ?? '';

if (!$userId) {
    echo json_encode(['success' => false]);
    exit;
}

if (!file_exists($data_file)) {
    echo json_encode(['success' => true]);
    exit;
}

$clicks = json_decode(file_get_contents($data_file), true) ?: [];

// Удаляем клики этого пользователя
$clicks = array_filter($clicks, function($click) use ($userId) {
    return $click['userId'] !== $userId;
});

file_put_contents($data_file, json_encode(array_values($clicks)));

echo json_encode(['success' => true]);
?>
