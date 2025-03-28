<?php

require_once '../admin/lib/config.php';
require_once '../admin/lib/class.php';

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
 * Phân tích nâng cao: Tìm IP có hoạt động đáng ngờ nhất
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
 * Đưa ra các đề xuất bảo mật
 */
function generate_security_recommendations($ddos_data, $nonexistent_php_data, $blocked_ips)
{
    $recommendations = [];

    // Đề xuất tăng giới hạn yêu cầu nếu có nhiều IP hợp pháp bị chặn
    if (count($blocked_ips) > 10) {
        $recommendations[] = "Xem xét tăng giới hạn yêu cầu tối đa để tránh chặn nhầm người dùng hợp pháp.";
    }

    // Đề xuất kiểm tra các IP đáng ngờ nhất
    $most_suspicious_ips = find_most_suspicious_ips($ddos_data, $nonexistent_php_data);
    if (!empty($most_suspicious_ips)) {
        $recommendations[] = "Kiểm tra hoạt động của các IP đáng ngờ nhất: " . implode(", ", array_keys($most_suspicious_ips)) . ".";
    }

    // Đề xuất thêm các quy tắc bảo mật
    $recommendations[] = "Cân nhắc sử dụng WAF (Web Application Firewall) để bảo vệ tốt hơn trước các cuộc tấn công phức tạp.";
    $recommendations[] = "Thường xuyên kiểm tra và cập nhật danh sách IP đáng tin cậy (whitelist).";

    return $recommendations;
}

/**
 * Chuẩn bị dữ liệu cho biểu đồ số lượng yêu cầu theo thời gian
 */
function prepare_chart_data($db)
{
    $result = $db->query_prepared(
        "SELECT FROM_UNIXTIME(timestamp, '%Y-%m-%d %H:00:00') as hour, COUNT(*) as count 
         FROM log_ddos_logs 
         GROUP BY hour 
         ORDER BY hour ASC"
    );
    $time_buckets = [];
    foreach ($result as $row) {
        $time_buckets[$row['hour']] = $row['count'];
    }

    return [
        'labels' => array_keys($time_buckets),
        'data' => array_values($time_buckets),
    ];
}

/**
 * Chuẩn bị dữ liệu cho biểu đồ phân tích IP đáng ngờ
 */
function prepare_suspicious_ip_chart_data($most_suspicious_ips)
{
    return [
        'labels' => array_keys($most_suspicious_ips),
        'data' => array_values($most_suspicious_ips),
    ];
}

/**
 * Hiển thị báo cáo
 */
function display_report($title, $data)
{
    echo "<div class='mb-8'>";
    echo "<h2 class='text-xl font-bold mb-4'>$title</h2>";
    if (empty($data)) {
        echo "<p class='text-gray-500'>Không có dữ liệu.</p>";
        return;
    }

    echo "<div class='overflow-x-auto'>";
    echo "<table class='table-auto border-collapse border border-gray-300 w-full'>";
    echo "<thead>";
    echo "<tr class='bg-gray-100'>";
    echo "<th class='border border-gray-300 px-4 py-2'>IP</th>";
    echo "<th class='border border-gray-300 px-4 py-2'>Chi tiết</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($data as $key => $value) {
        echo "<tr class='hover:bg-gray-50'>";
        echo "<td class='border border-gray-300 px-4 py-2'>$key</td>";
        if (is_array($value)) {
            echo "<td class='border border-gray-300 px-4 py-2'><ul class='list-disc pl-5'>";
            foreach ($value as $detail) {
                echo "<li>$detail</li>";
            }
            echo "</ul></td>";
        } else {
            echo "<td class='border border-gray-300 px-4 py-2'>$value</td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
}

// Phân tích dữ liệu từ cơ sở dữ liệu
$ddos_analysis = analyze_ddos_log($db);
$nonexistent_php_analysis = analyze_nonexistent_php_log($db);
$chart_data = prepare_chart_data($db);
$most_suspicious_ips = find_most_suspicious_ips($ddos_analysis, $nonexistent_php_analysis);
$suspicious_ip_chart_data = prepare_suspicious_ip_chart_data($most_suspicious_ips);
$security_recommendations = generate_security_recommendations($ddos_analysis, $nonexistent_php_analysis, []);

// Hiển thị báo cáo
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo các cuộc tấn công</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Báo cáo các cuộc tấn công</h1>
        <?php
        display_report("Phân tích DDoS (Số lượng yêu cầu từ mỗi IP)", $ddos_analysis);
        display_report("Truy cập vào file PHP không tồn tại", $nonexistent_php_analysis);
        display_report("Top 5 IP đáng ngờ nhất", $most_suspicious_ips);
        ?>
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Biểu đồ số lượng yêu cầu theo thời gian</h2>
            <canvas id="ddosChart" width="400" height="200"></canvas>
        </div>
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Biểu đồ phân tích IP đáng ngờ</h2>
            <canvas id="suspiciousIpChart" width="400" height="200"></canvas>
        </div>
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Đề xuất bảo mật</h2>
            <ul class="list-disc pl-5">
                <?php foreach ($security_recommendations as $recommendation): ?>
                    <li><?php echo $recommendation; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script>
        // Biểu đồ số lượng yêu cầu theo thời gian
        const ddosCtx = document.getElementById('ddosChart').getContext('2d');
        const ddosChart = new Chart(ddosCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_data['labels']); ?>,
                datasets: [{
                    label: 'Số lượng yêu cầu',
                    data: <?php echo json_encode($chart_data['data']); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Thời gian'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Số lượng yêu cầu'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ phân tích IP đáng ngờ
        const suspiciousIpCtx = document.getElementById('suspiciousIpChart').getContext('2d');
        const suspiciousIpChart = new Chart(suspiciousIpCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($suspicious_ip_chart_data['labels']); ?>,
                datasets: [{
                    label: 'Hoạt động đáng ngờ',
                    data: <?php echo json_encode($suspicious_ip_chart_data['data']); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'IP'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Hoạt động đáng ngờ'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>