<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    $filename = 'tabs_data.json';
    $lastTimestamp = isset($_GET['last']) ? intval($_GET['last']) : 0;
    
    // Перевірка існування файлу
    if (!file_exists($filename)) {
        echo json_encode([
            'updated' => false,
            'timestamp' => 0,
            'message' => 'Файл не знайдено'
        ]);
        exit;
    }
    
    // Читання файлу
    $content = file_get_contents($filename);
    $data = json_decode($content, true);
    
    if ($data === null) {
        echo json_encode([
            'updated' => false,
            'timestamp' => 0,
            'message' => 'Невірний JSON'
        ]);
        exit;
    }
    
    $currentTimestamp = $data['timestamp'] ?? 0;
    
    // Перевірка чи є оновлення
    $isUpdated = $currentTimestamp > $lastTimestamp;
    
    echo json_encode([
        'updated' => $isUpdated,
        'timestamp' => $currentTimestamp,
        'last_checked' => time()
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'updated' => false,
        'timestamp' => 0,
        'message' => 'Помилка: ' . $e->getMessage()
    ]);
}
?>
