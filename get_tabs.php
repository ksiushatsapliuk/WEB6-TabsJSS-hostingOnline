<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    $filename = 'tabs_data.json';
    
    // Перевірка існування файлу
    if (!file_exists($filename)) {
        echo json_encode([
            'success' => false,
            'tabs' => [],
            'timestamp' => 0,
            'message' => 'Файл даних не знайдено'
        ]);
        exit;
    }
    
    // Читання файлу
    $content = file_get_contents($filename);
    
    if ($content === false) {
        echo json_encode([
            'success' => false,
            'tabs' => [],
            'timestamp' => 0,
            'message' => 'Помилка читання файлу'
        ]);
        exit;
    }
    
    // Декодування JSON
    $data = json_decode($content, true);
    
    if ($data === null) {
        echo json_encode([
            'success' => false,
            'tabs' => [],
            'timestamp' => 0,
            'message' => 'Невірний формат JSON'
        ]);
        exit;
    }
    
    // Повернення даних
    echo json_encode([
        'success' => true,
        'tabs' => $data['tabs'] ?? [],
        'timestamp' => $data['timestamp'] ?? 0,
        'updated_at' => $data['updated_at'] ?? 'невідомо'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'tabs' => [],
        'timestamp' => 0,
        'message' => 'Помилка сервера: ' . $e->getMessage()
    ]);
}
?>
