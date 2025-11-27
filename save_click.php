<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$data_file = 'clicks.json';

// Автоматически создаем файл если его нет
if (!file_exists($data_file)) {
    file_put_contents($data_file, '[]');
}

// Получаем данные
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'No data']);
    exit;
}

// Загружаем существующие данные
$clicks = json_decode(file_get_contents($data_file), true) ?: [];

// Добавляем новый клик
$clicks[] = [
    'userId' => $input['userId'],
    'x' => intval($input['x']),
    'y' => intval($input['y']),
    'timestamp' => $input['timestamp'],
    'date' => date('Y-m-d')
];

// Сохраняем (ограничиваем размер до 10000 записей)
if (count($clicks) > 10000) {
    $clicks = array_slice($clicks, -5000);
}

if (file_put_contents($data_file, json_encode($clicks))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Cannot save file']);
}
?>
