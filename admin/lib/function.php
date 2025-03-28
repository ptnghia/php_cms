<?php
include "resize-class.php";
function resize_img($file, $option, $w, $h, $folder = 'thumb')
{
    $resizeObj = new resize('../uploads/images/' . $file);
    //Resize image (options: exact, portrait, landscape, auto, crop)
    $resizeObj->resizeImage($w, $h, $option);
    $resizeObj->saveImage('../uploads/images/' . $folder . '/' . $file, 100);
    return $file;
}
function get_size_img($file)
{
    $size = getimagesize($file);
    list($width, $height) = $size;
    return $width . 'x' . $height;
}
function get_json($object, $key = '', $attributes = '')
{
    $string = '{
    "lang":{
        "0":{
            "code":"vi",
            "name":"Tiếng việt",
            "image":"templates/images/icon_vn.webp",
            "price":"VND"
        }
    }
}';
    $arr_config = json_decode($string, true);

    // Use null coalescing operators to simplify logic
    if ($key === '') {
        return $arr_config[$object] ?? null;
    } else {
        return $attributes === '' ?
            ($arr_config[$object][$key] ?? null) : ($arr_config[$object][$key][$attributes] ?? null);
    }
}
function numberformat($number)
{
    return str_replace(',', '.', number_format($number));
}
function token()
{
    $token = bin2hex(random_bytes(20));
    $_SESSION['token'] = $token;
    return $token;
}
function getthoigiandang($ngaydang)
{
    $date1 = $ngaydang;
    $date2 = date('Y-m-d H:i:s', time());

    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));

    // Simplify time calculation using match expression (PHP 8)
    if ($years > 0) return $years . ' năm trước';
    if ($months > 0) return $months . ' tháng trước';
    if ($days > 0) return $days . ' ngày trước';
    if ($hours > 0) return $hours . ' giờ trước';
    if ($minutes > 0) return $minutes . ' phút trước';
    return $seconds . ' giây trước';
}
function catchuoi($text, $n = 80)
{
    // string is shorter than n, return as is
    $text = strip_tags($text);
    if (strlen($text) <= $n) {
        return $text;
    }
    $text = substr($text, 0, $n);
    if ($text[$n - 1] == ' ') {
        return trim($text) . "...";
    }
    $x  = explode(" ", $text);
    $sz = sizeof($x);
    if ($sz <= 1) {
        return $text . "...";
    }
    $x[$sz - 1] = '';
    return trim(implode(" ", $x)) . "...";
}

function bodautv($str)
{
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );

    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }

    // Combine multiple replacements into one for better performance
    $str = preg_replace(
        ["/( )/", "/(\?)/", "/(:)/", "/(&)/", "/,/", "/-+-/", '"/"'],
        ['-', '-', '-', '', '-', '-', '-'],
        $str
    );

    return trim($str, '-');
}
function replaceHTMLCharacter($str)
{
    $str  = preg_replace('/&/',        '&amp;',    $str);
    $str  = preg_replace('/</',        '&lt;',        $str);
    $str  = preg_replace('/>/',        '&gt;',        $str);
    $str  = preg_replace('/\"/',    '&quot;',    $str);
    $str  = preg_replace('/\'/',    '&apos;',    $str);
    return $str;
}

function chuoird($length)
{
    $str = '';
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0, $size - 1)];
    }
    return $str;
}
function chuoinum($length)
{
    $str = '';
    $chars = "0123456789";
    $size = strlen($chars);
    for ($i = 0; $length; $i++) {
        $str .= $chars[rand(0, $size - 1)];
    }
    return $str;
}
function check_ptram($giacu, $giamoi)
{
    return round((($giacu - $giamoi) * 100 / $giacu));
}
//upload_file
function Uploadfile($file, $type, $folder, $name)
{
    if (isset($_FILES[$file]) && !$_FILES[$file]['error']) {
        $error = 0;
        $duoi = explode('.', $_FILES[$file]['name']); // tách chuỗi khi gặp dấu .
        $duoi = $duoi[(count($duoi) - 1)]; //lấy ra đuôi file

        $file_type = $_FILES[$file]["type"];
        $file_size = $_FILES[$file]["size"];
        $limit_size = 2000000;
        if ($type == 'file') {
            $limit_size = 5000000;
        }
        if ($file_size < $limit_size) {
            if ($type == 'images') {
                if (in_array($file_type, ['image/svg', 'image/webp', 'image/jpg', 'image/png', 'image/jpeg', 'image/gif'])) {
                    $error = $error + 0;
                } else {
                    $error = $error + 1;
                }
            } elseif ($type == 'file') {
                if (in_array($file_type, ['audio/mpeg', 'video/mp4', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
                    $error = $error + 0;
                } else {
                    $error = $error + 1;
                }
            }
            if ($error == 0) {
                $file_name = $name . '.' . $duoi;
                if (!file_exists($folder . $file_name)) {
                    $file_name_news = $file_name;
                } else {
                    $file_name_news = $name . '-' . rand(1, 999) . '.' . $duoi;
                }

                if (move_uploaded_file($_FILES[$file]['tmp_name'], $folder . $file_name_news)) {
                    return $file_name_news;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function multiple_Uploadfile($file, $type, $folder, $name)
{
    $total = count($_FILES[$file]['name']);
    //return $_FILES[$file]['tmp_name'][1];
    $list_file = '';
    for ($i = 0; $i < $total; $i++) {

        if ($_FILES[$file]['name'][$i] != '' && !$_FILES[$file][$i]['error']) {
            $error = 0;
            $duoi = explode('.', $_FILES[$file]['name'][$i]); // tách chuỗi khi gặp dấu .
            $duoi = $duoi[(count($duoi) - 1)]; //lấy ra đuôi file

            $file_type = $_FILES[$file]["type"][$i];
            $file_size = $_FILES[$file]["size"][$i];
            $limit_size = 2000000;
            if ($type == 'file') {
                $limit_size = 5000000;
            }
            if ($file_size < $limit_size) {
                if ($type == 'images') {
                    if (in_array($file_type, ['image/svg', 'image/webp', 'image/jpg', 'image/png', 'image/jpeg', 'image/gif'])) {
                        $error = $error + 0;
                    } else {
                        $error = $error + 1;
                    }
                } elseif ($type == 'file') {
                    if (in_array($file_type, ['audio/mpeg', 'video/mp4', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
                        $error = $error + 0;
                    } else {
                        $error = $error + 1;
                    }
                }
                if ($error == 0) {
                    $file_name = $name . '-' . rand(1, 999) . '.' . $duoi;
                    if (!file_exists($folder . $file_name)) {
                        $file_name_news = $file_name;
                    } else {
                        $file_name_news = $i . '-' . $file_name;
                    }
                    if (move_uploaded_file($_FILES[$file]['tmp_name'][$i], $folder . $file_name_news)) {
                        $list_file .= $file_name_news . ',';
                    } else {
                        $list_file .= '';
                    }
                } else {
                    $list_file .= '';
                }
            } else {
                $list_file .= '';
            }
        } else {
            $list_file .= '';
        }
    }
    $list_file = trim($list_file, ',');
    return $list_file;
}
function check_shell($text)
{
    $arr_list = ['<?php', 'eval(', 'base64', '$_F=__FILE__;', 'readdir(', 'ini_get', '<form', '<input', '<button'];

    // Optimize with array_reduce instead of loop
    $matches = array_filter($arr_list, fn($pattern) => strpos($text, $pattern) !== false);
    return empty($matches) ? $text : "";
}
function doi_ngay($date)
{
    $thang = date('m', $date);
    $ngay = date('d', $date);
    $name = date('Y', $date);

    // Use array lookup instead of switch for better performance
    $months = [
        '01' => 'Tháng 1',
        '02' => 'Tháng 2',
        '03' => 'Tháng 3',
        '04' => 'Tháng 4',
        '05' => 'Tháng 5',
        '06' => 'Tháng 6',
        '07' => 'Tháng 7',
        '08' => 'Tháng 8',
        '09' => 'Tháng 9',
        '10' => 'Tháng 10',
        '11' => 'Tháng 11',
        '12' => 'Tháng 12',
    ];

    $tg = $months[$thang] ?? "";
    return $ngay . ' ' . $tg . ', ' . $name;
}
function check_phone($text)
{
    // Use a single regex pattern to validate the phone number
    return preg_match('/^0[0-9]{8,10}$/', $text) ? 0 : 1;
}
include 'simple_html_dom.php';

function validate_content($text, $redirect_on_invalid = true)
{
    // Return early if empty
    if ($text === '') {
        return '';
    }

    // First level of defense - basic string cleanup
    $text = trim($text);

    // Function to handle invalid content
    $handle_invalid = function ($reason) use ($redirect_on_invalid) {
        // Log the invalid content attempt
        $time = date('Y-m-d H:i:s');
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $request_uri = $_SERVER['REQUEST_URI'];

        $log_message = "[$time] Invalid Content ($reason): IP: $user_ip, Agent: $user_agent, URL: $request_uri\n";
        error_log($log_message, 3, dirname(__FILE__) . '/../../log/security.log');

        if ($redirect_on_invalid) {
            // Redirect to the same page to stop processing
            if (headers_sent()) {
                echo '<script>alert("Phát hiện dữ liệu không hợp lệ!");</script>';
                echo '<script>window.location.href="' . $_SERVER['PHP_SELF'] . '";</script>';
                exit;
            } else {
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        }

        return '';
    };

    // SQL Injection protection patterns
    $sql_patterns = [
        '/SELECT.*FROM/i',
        '/INSERT\s+INTO/i',
        '/UPDATE.*SET/i',
        '/DELETE\s+FROM/i',
        '/DROP\s+TABLE/i',
        '/UNION\s+SELECT/i',
        '/UNION\s+ALL\s+SELECT/i',
        '/OR\s+1=1/i',
        '/OR\s+\'1\'=\'1/i',
        '/--/i',
        '/;/i',
        '/\/\*/i',
        '/\*\//i',
        // Thêm các mẫu Oracle SQL Injection
        '/\|\|/',                          // Oracle string concatenation
        '/dbms_pipe/i',                    // Oracle DBMS_PIPE package
        '/receive_message/i',              // Oracle RECEIVE_MESSAGE function
        '/chr\s*\(\s*\d+\s*\)/i',          // CHR() function
        '/utl_/i',                         // Oracle UTL_ packages
        '/sys\./i',                        // Oracle SYS schema
        '/to_char/i'                       // Oracle TO_CHAR function
    ];

    // Check for SQL injection patterns
    foreach ($sql_patterns as $pattern) {
        if (preg_match($pattern, $text)) {
            return $handle_invalid("SQL Injection");
        }
    }

    // Oracle-specific detection
    if (
        (strpos(strtolower($text), 'dbms_pipe') !== false ||
            strpos(strtolower($text), 'receive_message') !== false) &&
        (strpos(strtolower($text), 'chr') !== false ||
            strpos(strtolower($text), '||') !== false)
    ) {
        return $handle_invalid("Oracle SQL Injection");
    }

    // Check for combination of single quotes and || operator (Oracle injection)
    if (strpos($text, "'") !== false && strpos($text, "||") !== false) {
        return $handle_invalid("Oracle SQL Injection Pattern");
    }

    // Spam detection (uncommon characters in high volume)
    $spam_chars = str_split('§±¬¦¡¿ªº«»¤¶¢£¥®©™');
    $spam_char_count = 0;
    foreach ($spam_chars as $char) {
        $spam_char_count += substr_count($text, $char);
    }

    // If too many uncommon characters or text is extremely long
    if ($spam_char_count > 10 || strlen($text) > 10000) {
        return $handle_invalid("Spam Detection");
    }

    // Escape characters for SQL safety regardless of other cleaning
    $text = addslashes($text); // Basic SQL escaping

    // Remove all HTML and check for links
    $clean_text = strip_tags($text);

    // Check for URLs more thoroughly
    if (preg_match('/(http|https|ftp|mailto):|(<a|href=)/i', $text)) {
        return $handle_invalid("URL Detection");
    }

    return htmlspecialchars($clean_text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

define("LANG",  get_json('lang', '0', 'code'));

function generate_captcha()
{
    $captcha_code = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $chars_length = strlen($chars);
    for ($i = 0; $i < 6; $i++) {
        $captcha_code .= $chars[rand(0, $chars_length - 1)];
    }
    $_SESSION['captcha'] = $captcha_code;
    return $captcha_code;
}

function validate_captcha($input)
{
    return isset($_SESSION['captcha']) && $_SESSION['captcha'] === $input;
}
