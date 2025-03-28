<?php

$max_requests = 100; // Số lượng yêu cầu tối đa được phép
$block_duration = 3600; // Thời gian chặn tính bằng giây
$max_nonexistent_attempts = 3; // Số lần truy cập tối đa vào file PHP không tồn tại trước khi bị chặn
$auto_unblock_duration = 86400; // Tự động bỏ chặn sau 24 giờ
$suspicious_patterns = array(
    '/(?:exec|system|shell_exec|passthru|eval)\s*\(/i', // Hàm PHP nguy hiểm
    '/(?:SELECT|INSERT|UPDATE|DELETE|DROP|UNION)\s+/i', // SQL Injection
    '/<script[^>]*>.*?<\/script>/i', // XSS
    '/etc\/passwd/i' // Path traversal
);

/**
 * Kiểm tra User-Agent có phải bot hợp lệ không
 */
function is_valid_bot($user_agent)
{
    $valid_bots = [
        'Googlebot',
        'Bingbot',
        'Slurp', // Yahoo
        'DuckDuckBot',
        'Baiduspider',
        'YandexBot'
    ];

    foreach ($valid_bots as $bot) {
        if (stripos($user_agent, $bot) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Ghi log yêu cầu vào cơ sở dữ liệu
 */
function log_request($ip, $request_uri, $db)
{
    $timestamp = time();
    $db->query_prepared(
        "INSERT INTO log_ddos_logs (ip, request_uri, timestamp) VALUES (?, ?, ?)",
        [$ip, $request_uri, $timestamp]
    );
}

/**
 * Kiểm tra IP đã bị chặn chưa
 */
function is_blocked($ip, $db)
{
    $result = $db->query_prepared(
        "SELECT COUNT(*) as count FROM log_blocked_ips WHERE ip = ?",
        [$ip]
    );
    return $result[0]['count'] > 0;
}

/**
 * Thêm IP vào danh sách chặn
 */
function block_ip($ip, $db)
{
    $timestamp = time();
    $db->query_prepared(
        "INSERT INTO log_blocked_ips (ip, timestamp) VALUES (?, ?)",
        [$ip, $timestamp]
    );
}

/**
 * Xóa các IP hết thời gian chặn
 */
function clean_expired_blocks($db, $auto_unblock_duration)
{
    $current_time = time();
    $expired_ips = $db->query_prepared(
        "SELECT ip FROM log_blocked_ips WHERE timestamp < ?",
        [$current_time - $auto_unblock_duration]
    );

    foreach ($expired_ips as $row) {
        $db->query_prepared("DELETE FROM log_blocked_ips WHERE ip = ?", [$row['ip']]);
    }
}

/**
 * Kiểm tra số lượng yêu cầu từ IP trong khoảng thời gian
 */
function count_requests($ip, $db, $block_duration)
{
    $current_time = time();
    $result = $db->query_prepared(
        "SELECT COUNT(*) as count FROM log_ddos_logs WHERE ip = ? AND timestamp > ?",
        [$ip, $current_time - $block_duration]
    );
    return $result[0]['count'];
}

/**
 * Kiểm tra truy cập vào file PHP không tồn tại
 */
function count_nonexistent_php_attempts($ip, $db, $block_duration)
{
    $current_time = time();
    $result = $db->query_prepared(
        "SELECT COUNT(*) as count FROM log_nonexistent_php_logs WHERE ip = ? AND timestamp > ?",
        [$ip, $current_time - $block_duration]
    );
    return $result[0]['count'];
}

/**
 * Ghi log truy cập vào file PHP không tồn tại
 */
function log_nonexistent_php_access($ip, $request_uri, $db)
{
    $timestamp = time();
    $db->query_prepared(
        "INSERT INTO log_nonexistent_php_logs (ip, request_uri, timestamp) VALUES (?, ?, ?)",
        [$ip, $request_uri, $timestamp]
    );
}

/**
 * Hàm chính xử lý tất cả các biện pháp bảo vệ website
 */
function execute_protection($db)
{
    global $max_requests, $block_duration, $max_nonexistent_attempts, $auto_unblock_duration, $suspicious_patterns;

    $user_ip = $_SERVER['REMOTE_ADDR'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Bỏ qua kiểm tra nếu là sitemap.xml
    if (basename($request_uri) === 'sitemap.xml') {
        return;
    }

    // Bỏ qua kiểm tra nếu là bot hợp lệ
    if (is_valid_bot($user_agent)) {
        return;
    }

    // Dọn dẹp các IP bị chặn đã hết hạn
    clean_expired_blocks($db, $auto_unblock_duration);

    // Ghi nhận yêu cầu hiện tại
    log_request($user_ip, $request_uri, $db);

    // Kiểm tra các mẫu nguy hiểm trong yêu cầu
    foreach ($suspicious_patterns as $pattern) {
        if (preg_match($pattern, $request_uri)) {
            block_ip($user_ip, $db);
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied: Suspicious request pattern detected.');
        }
    }

    // Kiểm tra truy cập vào file PHP không tồn tại
    if (strpos($request_uri, '.php') !== false && !file_exists($_SERVER['DOCUMENT_ROOT'] . parse_url($request_uri, PHP_URL_PATH))) {
        log_nonexistent_php_access($user_ip, $request_uri, $db);

        if (count_nonexistent_php_attempts($user_ip, $db, $block_duration) >= $max_nonexistent_attempts) {
            block_ip($user_ip, $db);
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied: Suspicious access to non-existent PHP files detected.');
        }
    }

    // Kiểm tra số lượng yêu cầu từ IP
    if (count_requests($user_ip, $db, $block_duration) >= $max_requests) {
        block_ip($user_ip, $db);
        header('HTTP/1.1 403 Forbidden');
        exit('Access denied: Too many requests.');
    }

    // Kiểm tra IP đã bị chặn chưa
    if (is_blocked($user_ip, $db)) {
        header('HTTP/1.1 403 Forbidden');
        exit('Access denied: IP blocked.');
    }
}

// Thực thi tất cả các biện pháp bảo vệ
execute_protection($db);
