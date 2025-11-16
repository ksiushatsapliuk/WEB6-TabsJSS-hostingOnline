<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Тільки POST запити']);
    exit;
}

try {
    // Отримання даних з POST запиту
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!isset($data['tabs']) || !is_array($data['tabs'])) {
        echo json_encode(['success' => false, 'message' => 'Невірний формат даних']);
        exit;
    }
    
    // Валідація вкладок
    foreach ($data['tabs'] as $tab) {
        if (!isset($tab['title']) || !isset($tab['content'])) {
            echo json_encode(['success' => false, 'message' => 'Кожна вкладка повинна мати title та content']);
            exit;
        }
    }
    
    // Підготовка даних для збереження
    $saveData = [
        'tabs' => $data['tabs'],
        'timestamp' => time(),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Збереження у JSON файл
    $filename = 'tabs_data.json';
    $json = json_encode($saveData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($filename, $json) === false) {
        echo json_encode(['success' => false, 'message' => 'Помилка запису файлу']);
        exit;
    }
    
    // Встановлення прав доступу (якщо потрібно)
    @chmod($filename, 0644);
    
    echo json_encode([
        'success' => true,
        'message' => 'Дані успішно збережено',
        'timestamp' => $saveData['timestamp'],
        'count' => count($data['tabs'])
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Помилка сервера: ' . $e->getMessage()
    ]);
}
?>
