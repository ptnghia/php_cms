<?php

require_once '../admin/lib/config.php';
require_once '../admin/lib/class.php';
require_once '../smtp/index.php';

$db = new func_index($config['database']); // Kết nối cơ sở dữ liệu

/**
 * Phân tích log DDoS từ cơ sở dữ liệu
 */
function analyze_ddos_log($db)
{
    $result = $db->query_prepared(
        "SELECT ip, COUNT(*) as count FROM log_ddos_logs GROUP BY ip"
    );
    $ip_counts = [];
    foreach ($result as $row) {
        $ip_counts[$row['ip']] = $row['count'];
    }
    return $ip_counts;
}

/**
 * Phân tích log truy cập file PHP không tồn tại từ cơ sở dữ liệu
 */
function analyze_nonexistent_php_log($db)
{
    $result = $db->query_prepared(
        "SELECT ip, request_uri FROM log_nonexistent_php_logs"
    );
    $ip_attempts = [];
    foreach ($result as $row) {
        if (!isset($ip_attempts[$row['ip']])) {
            $ip_attempts[$row['ip']] = [];
        }
        $ip_attempts[$row['ip']][] = $row['request_uri'];
    }
    return $ip_attempts;
}

/**
 * Tìm IP có hoạt động đáng ngờ nhất
 */
function find_most_suspicious_ips($ddos_data, $nonexistent_php_data)
{
    $suspicious_ips = [];
    foreach ($ddos_data as $ip => $count) {
        $suspicious_ips[$ip] = $count;
    }
    foreach ($nonexistent_php_data as $ip => $attempts) {
        if (!isset($suspicious_ips[$ip])) {
            $suspicious_ips[$ip] = 0;
        }
        $suspicious_ips[$ip] += count($attempts);
    }
    arsort($suspicious_ips); // Sắp xếp giảm dần theo mức độ đáng ngờ
    return array_slice($suspicious_ips, 0, 5, true); // Lấy top 5 IP đáng ngờ nhất
}

/**
 * Tạo báo cáo HTML
 */
function generate_attack_report($ddos_analysis, $nonexistent_php_analysis, $most_suspicious_ips)
{
    $report = "<h1>Báo cáo các cuộc tấn công</h1>";
    $report .= "<h2>Phân tích DDoS</h2><ul>";
    foreach ($ddos_analysis as $ip => $count) {
        $report .= "<li>$ip - $count yêu cầu</li>";
    }
    $report .= "</ul>";

    $report .= "<h2>Top 5 IP đáng ngờ nhất</h2><ul>";
    foreach ($most_suspicious_ips as $ip => $count) {
        $report .= "<li>$ip - $count hoạt động đáng ngờ</li>";
    }
    $report .= "</ul>";

    $report .= "<h2>Truy cập vào file PHP không tồn tại</h2><ul>";
    foreach ($nonexistent_php_analysis as $ip => $attempts) {
        $report .= "<li>$ip: " . implode(', ', $attempts) . "</li>";
    }
    $report .= "</ul>";

    return $report;
}

/**
 * Xóa dữ liệu cũ trong cơ sở dữ liệu
 */
function clean_old_logs($db)
{
    $current_time = time();
    $one_month_ago = $current_time - (30 * 24 * 60 * 60); // 1 tháng trước

    // Xóa log DDoS cũ
    $db->query_prepared(
        "DELETE FROM log_ddos_logs WHERE timestamp < ?",
        [$one_month_ago]
    );

    // Xóa log truy cập file PHP không tồn tại cũ
    $db->query_prepared(
        "DELETE FROM log_nonexistent_php_logs WHERE timestamp < ?",
        [$one_month_ago]
    );

    // Ghi log việc dọn dẹp
    error_log("Dọn dẹp dữ liệu log cũ thành công: " . date('Y-m-d H:i:s', $current_time));
}

/**
 * Kiểm tra và ghi log các bot bị chặn nhầm
 */
function log_blocked_bots($db)
{
    $result = $db->query_prepared(
        "SELECT ip, user_agent FROM log_blocked_ips"
    );

    foreach ($result as $row) {
        if (is_valid_bot($row['user_agent'])) {
            error_log("Bot hợp lệ bị chặn nhầm: IP {$row['ip']}, User-Agent: {$row['user_agent']}");
        }
    }
}

// Thực hiện dọn dẹp dữ liệu cũ
clean_old_logs($db);

// Thực hiện kiểm tra bot bị chặn nhầm
log_blocked_bots($db);

// Phân tích dữ liệu từ cơ sở dữ liệu
$ddos_analysis = analyze_ddos_log($db);
$nonexistent_php_analysis = analyze_nonexistent_php_log($db);
$most_suspicious_ips = find_most_suspicious_ips($ddos_analysis, $nonexistent_php_analysis);

// Tạo báo cáo
$report = generate_attack_report($ddos_analysis, $nonexistent_php_analysis, $most_suspicious_ips);

// Gửi email nếu có dấu hiệu tấn công
if (!empty($most_suspicious_ips)) {
    sendmail(
        "Báo cáo các cuộc tấn công",
        $report,
        "no-reply@lifetech.com",
        "kythuat.lifetech@gmail.com",
        "Hệ thống bảo mật"
    );
}
