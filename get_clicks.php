<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data_file = 'clicks.json';

// Автоматически создаем файл если его нет
if (!file_exists($data_file)) {
    file_put_contents($data_file, '[]');
}

$clicks = json_decode(file_get_contents($data_file), true) ?: [];

// Статистика
$userIds = array_column($clicks, 'userId');
$uniqueUsers = count(array_unique($userIds));
$today = date('Y-m-d');
$todayClicks = count(array_filter($clicks, function($click) use ($today) {
    return $click['date'] === $today;
}));

// Самый активный пользователь
$userCounts = array_count_values($userIds);
arsort($userCounts);
$topUser = key($userCounts) ? substr(key($userCounts), 0, 8) . '...' : null;

// Дни активности
$activeDays = count(array_unique(array_column($clicks, 'date')));

// Последняя активность
$lastClick = end($clicks);
$lastActivity = $lastClick ? date('H:i:s', strtotime($lastClick['timestamp'])) : null;

$stats = [
    'totalClicks' => count($clicks),
    'uniqueUsers' => $uniqueUsers,
    'todayClicks' => $todayClicks,
    'topUser' => $topUser,
    'daysActive' => $activeDays,
    'lastActivity' => $lastActivity
];

echo json_encode([
    'success' => true,
    'clicks' => $clicks,
    'stats' => $stats
]);
?>
