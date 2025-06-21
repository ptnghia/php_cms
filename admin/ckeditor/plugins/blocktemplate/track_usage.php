<?php
// Track block usage for analytics
header('Content-Type: application/json');
session_start();

// Security check
if (!isset($_SESSION["user_hash"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['blockId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

try {
    // Usage tracking file
    $usageFile = __DIR__ . '/usage_analytics.json';
    
    // Read current usage data
    $usageData = [];
    if (file_exists($usageFile)) {
        $content = file_get_contents($usageFile);
        $usageData = json_decode($content, true) ?: [];
    }
    
    // Initialize structure if needed
    if (!isset($usageData['blocks'])) {
        $usageData['blocks'] = [];
    }
    
    $blockId = $data['blockId'];
    $timestamp = $data['timestamp'] ?? time() * 1000; // milliseconds
    $editor = $data['editor'] ?? 'unknown';
    $user = $_SESSION['username'] ?? 'unknown';
    
    // Initialize block usage if not exists
    if (!isset($usageData['blocks'][$blockId])) {
        $usageData['blocks'][$blockId] = [
            'total_uses' => 0,
            'first_used' => date('Y-m-d H:i:s'),
            'last_used' => date('Y-m-d H:i:s'),
            'usage_history' => []
        ];
    }
    
    // Update usage statistics
    $usageData['blocks'][$blockId]['total_uses']++;
    $usageData['blocks'][$blockId]['last_used'] = date('Y-m-d H:i:s');
    
    // Add to history (keep last 100 entries)
    $usageData['blocks'][$blockId]['usage_history'][] = [
        'timestamp' => $timestamp,
        'date' => date('Y-m-d H:i:s'),
        'user' => $user,
        'editor' => $editor,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    // Keep only last 100 usage entries per block
    if (count($usageData['blocks'][$blockId]['usage_history']) > 100) {
        $usageData['blocks'][$blockId]['usage_history'] = array_slice(
            $usageData['blocks'][$blockId]['usage_history'], 
            -100
        );
    }
    
    // Update global statistics
    if (!isset($usageData['global'])) {
        $usageData['global'] = [
            'total_uses' => 0,
            'unique_blocks_used' => 0,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    $usageData['global']['total_uses']++;
    $usageData['global']['unique_blocks_used'] = count($usageData['blocks']);
    $usageData['global']['last_updated'] = date('Y-m-d H:i:s');
    
    // Save usage data
    $jsonContent = json_encode($usageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($usageFile, $jsonContent);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Usage tracked successfully',
        'total_uses' => $usageData['blocks'][$blockId]['total_uses']
    ]);
    
} catch (Exception $e) {
    error_log('Block Template Usage Tracking Error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to track usage',
        'message' => $e->getMessage()
    ]);
}
?>
