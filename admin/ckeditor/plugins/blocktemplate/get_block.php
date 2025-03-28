<?php
// Thiết lập response dạng JSON
header('Content-Type: application/json');

// Kiểm tra ID được truyền vào
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu tham số ID']);
    exit;
}

$blockId = $_GET['id'];

// Đường dẫn đến file JSON
$blocksFile = __DIR__ . '/blocks.json';

// Đọc dữ liệu blocks
if (file_exists($blocksFile)) {
    $content = file_get_contents($blocksFile);
    $blocksData = json_decode($content, true);

    // Tìm block theo ID
    foreach ($blocksData['blocks'] as $block) {
        if ($block['id'] === $blockId) {
            // Trả về thông tin block
            echo json_encode($block);
            exit;
        }
    }

    // Nếu không tìm thấy
    http_response_code(404);
    echo json_encode(['error' => 'Không tìm thấy block với ID ' . $blockId]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Không thể đọc file dữ liệu blocks']);
}
