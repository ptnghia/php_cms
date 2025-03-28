<?php
// Enable error display during development
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Import namespaces at the top level
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// SMTP Configuration - Better to move these to a separate config file
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'kythuat.lifetech@gmail.com');
define('SMTP_PASS', 'siejmceybagomaox'); // Consider using environment variables for better security

function sendmail($tieude, $noidung, $nguoigui, $nguoinhan, $tennguoigui, $debug = false)
{
    // Validate email addresses
    if (!filter_var($nguoigui, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid sender email: $nguoigui");
        return "Error: Invalid sender email format";
    }

    if (!filter_var($nguoinhan, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid recipient email: $nguoinhan");
        return "Error: Invalid recipient email format";
    }

    $mail = new PHPMailer(true); // true enables exceptions

    try {
        // Enable debug output if requested
        if ($debug) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        }

        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host       = SMTP_HOST;
        $mail->Port       = SMTP_PORT;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;

        // Set charset to ensure proper encoding
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($nguoigui, $tennguoigui);
        $mail->Subject    = $tieude;
        $mail->msgHTML($noidung);
        $mail->addAddress($nguoinhan, $tieude);

        if (!$mail->send()) {
            error_log("PHPMailer error: " . $mail->ErrorInfo);
            return "Error: " . $mail->ErrorInfo;
        }

        if ($debug) {
            error_log("Email sent successfully to: $nguoinhan");
        }

        return true;
    } catch (Exception $e) {
        error_log("PHPMailer exception: " . $e->getMessage() . " | " . $mail->ErrorInfo);
        return "Error: " . $e->getMessage();
    }
}
// Test code remains the same
//$send_email = sendmail('test email', 'nội dung email test', 'team.vinrice@gmail.com', 'ptnghia.dev@gmail.com', 'team.vinrice');
//if ($send_email === true) {
//    echo 'send email success';
//} else {
//    echo 'send email fail: ' . $send_email;
//}

/**
 * Kiểm tra SMTP của email có tồn tại hay không
 * @param string $email
 * @param bool $quick Only check email format and domain MX record (faster)
 * @return array
 */
function checkSMTPEmail($email, $quick = true)
{
    // Anti-spam protection
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // File paths for blacklists and tracking
    $blacklist_dir = __DIR__ . '/spam_protection';
    if (!is_dir($blacklist_dir)) {
        mkdir($blacklist_dir, 0755, true);
    }

    $blacklist_email_file = $blacklist_dir . '/blacklist_emails.txt';
    $blacklist_ip_file = $blacklist_dir . '/blacklist_ips.txt';
    $email_tracking_file = $blacklist_dir . '/email_tracking.json';
    $ip_tracking_file = $blacklist_dir . '/ip_tracking.json';

    // Create files if they don't exist
    foreach ([$blacklist_email_file, $blacklist_ip_file] as $file) {
        if (!file_exists($file)) {
            file_put_contents($file, '');
        }
    }
    foreach ([$email_tracking_file, $ip_tracking_file] as $file) {
        if (!file_exists($file)) {
            file_put_contents($file, '{}');
        }
    }

    // Helper function to blacklist both email and IP
    $blacklistBoth = function ($reason) use ($email, $ip, $blacklist_email_file, $blacklist_ip_file) {
        // Add email to blacklist if not already there
        $blacklisted_emails = file($blacklist_email_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        if (!in_array($email, $blacklisted_emails)) {
            file_put_contents($blacklist_email_file, $email . PHP_EOL, FILE_APPEND);
        }

        // Add IP to blacklist if not already there
        $blacklisted_ips = file($blacklist_ip_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        if (!in_array($ip, $blacklisted_ips)) {
            file_put_contents($blacklist_ip_file, $ip . PHP_EOL, FILE_APPEND);
        }

        return [
            'email' => $email,
            'valid' => false,
            'message' => "Invalid email: $reason. This email and IP have been blacklisted.",
        ];
    };

    // Check if email or IP is blacklisted
    $blacklisted_emails = file($blacklist_email_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    $blacklisted_ips = file($blacklist_ip_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    if (in_array($email, $blacklisted_emails) || in_array($ip, $blacklisted_ips)) {
        // Return error for blacklisted email/IP
        return [
            'email' => $email,
            'valid' => false,
            'message' => 'This email or IP has been blocked due to excessive requests',
        ];
    }

    // Load tracking data
    $email_tracking = json_decode(file_get_contents($email_tracking_file), true) ?: [];
    $ip_tracking = json_decode(file_get_contents($ip_tracking_file), true) ?: [];

    $current_time = time();
    $hour_ago = $current_time - 3600; // 1 hour ago
    $day_ago = $current_time - 86400; // 24 hours ago

    // Initialize tracking if not exists
    if (!isset($email_tracking[$email])) {
        $email_tracking[$email] = [];
    }
    if (!isset($ip_tracking[$ip])) {
        $ip_tracking[$ip] = [];
    }

    // Clean up old entries (older than a day)
    $email_tracking[$email] = array_filter($email_tracking[$email], function ($timestamp) use ($day_ago) {
        return $timestamp >= $day_ago;
    });

    $ip_tracking[$ip] = array_filter($ip_tracking[$ip], function ($timestamp) use ($day_ago) {
        return $timestamp >= $day_ago;
    });

    // Count submissions within timeframes
    $email_hour_count = count(array_filter($email_tracking[$email], function ($timestamp) use ($hour_ago) {
        return $timestamp >= $hour_ago;
    }));

    $ip_hour_count = count(array_filter($ip_tracking[$ip], function ($timestamp) use ($hour_ago) {
        return $timestamp >= $hour_ago;
    }));

    $email_day_count = count($email_tracking[$email]);
    $ip_day_count = count($ip_tracking[$ip]);

    // Check limits and blacklist if needed
    if ($email_hour_count >= 3) {
        // Blacklist email
        file_put_contents($blacklist_email_file, $email . PHP_EOL, FILE_APPEND);
        return [
            'email' => $email,
            'valid' => false,
            'message' => 'This email has been blocked for exceeding hourly submission limits',
        ];
    }

    if ($ip_hour_count >= 3) {
        // Blacklist IP
        file_put_contents($blacklist_ip_file, $ip . PHP_EOL, FILE_APPEND);
        return [
            'email' => $email,
            'valid' => false,
            'message' => 'Your IP address has been blocked for exceeding hourly submission limits',
        ];
    }

    if ($email_day_count >= 5 || $ip_day_count >= 5) {
        return [
            'email' => $email,
            'valid' => false,
            'message' => 'Daily submission limit reached. Please try again tomorrow',
        ];
    }

    // Add current attempt to tracking
    $email_tracking[$email][] = $current_time;
    $ip_tracking[$ip][] = $current_time;

    // Save tracking data
    file_put_contents($email_tracking_file, json_encode($email_tracking));
    file_put_contents($ip_tracking_file, json_encode($ip_tracking));

    $result = [
        'email' => $email,
        'valid' => false,
        'message' => '',
    ];

    // Basic format check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $blacklistBoth("Invalid email format");
    }

    // Extract domain from email
    list(, $domain) = explode('@', $email);

    // Check MX Record for the domain
    if (!checkdnsrr($domain, 'MX')) {
        return $blacklistBoth("No MX record found for domain $domain");
    }

    // Kiểm tra domain email không phải là temp mail
    $disposable_domains = [
        'tempmail.com',
        'temp-mail.org',
        'disposablemail.com',
        'mailinator.com',
        'getnada.com',
        '10minutemail.com',
        'guerrillamail.com',
        'sharklasers.com',
        'yopmail.com',
        'dispostable.com',
        'mailnesia.com',
        'tempinbox.com',
        'safetymail.info',
        'mytrashmail.com',
        'trashmail.com',
        'spamgourmet.com'
    ];

    if (in_array($domain, $disposable_domains)) {
        return $blacklistBoth("Disposable email domains are not allowed");
    }

    // Quick check passes at this point - đủ cho hầu hết trường hợp
    if ($quick) {
        $result['valid'] = true;
        $result['message'] = "Email format is valid and domain has MX records";
        return $result;
    }

    // Nếu muốn kiểm tra kết nối SMTP thực tế (không khuyến khích)
    // Sử dụng phương pháp socket trực tiếp thay vì PHPMailer
    try {
        // Lấy danh sách MX records để tìm mail server chính xác
        getmxrr($domain, $mxhosts, $mxweights);

        if (empty($mxhosts)) {
            // Thử kết nối trực tiếp đến domain nếu không tìm thấy MX record
            $mxhosts[] = $domain;
        }

        // Sắp xếp các máy chủ MX theo thứ tự ưu tiên
        if (!empty($mxweights)) {
            array_multisort($mxweights, $mxhosts);
        }

        $timeout = 5; // giảm timeout để tránh chờ quá lâu

        // Thử kết nối đến các máy chủ MX
        foreach ($mxhosts as $host) {
            $socket = @fsockopen($host, 25, $errno, $errstr, $timeout);
            if ($socket) {
                // Đọc phản hồi ban đầu
                $response = fgets($socket);

                // Đóng kết nối
                fclose($socket);

                $result['valid'] = true;
                $result['message'] = "Successfully connected to mail server: $host";
                return $result;
            }
        }

        $result['message'] = "Could not connect to any mail server for domain $domain";
        if (!$quick) {
            // If we're doing a full check and can't connect to mail server, blacklist
            return $blacklistBoth("Could not connect to any mail server for domain $domain");
        }
        return $result;
    } catch (Exception $e) {
        $result['message'] = "Error during SMTP check: " . $e->getMessage();
        if (!$quick) {
            // If we're doing a full check and got an error, blacklist
            return $blacklistBoth("Error during SMTP check: " . $e->getMessage());
        }
        return $result;
    }

    return $result;
}
