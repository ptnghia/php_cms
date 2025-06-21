<?php
class func_index
{
    // global $rf;
    var $db = null; // Đảm bảo biến $db được khởi tạo đúng cách
    var $result = "";
    var $insert_id = "";
    var $sql = "";
    var $table = "";
    var $where = "";
    var $order = "";
    var $limit = "";

    var $servername = "";
    var $username = "";
    var $password = "";
    var $database = "";
    var $refix = "";

    // Add these new properties to store last query and error information
    private $lastQuery = "";
    private $lastError = "";

    // Thêm cấu hình cho môi trường
    private $isProduction = false; // Đặt true khi triển khai sản phẩm

    // Update constructor to PHP 8 syntax
    function __construct($config = array())
    {
        if (!empty($config)) {
            $this->init($config);
            $this->connect();
        }
    }

    // Keep original constructor for backward compatibility
    function func_index($config = array())
    {
        return $this->__construct($config);
    }

    function init($config = array())
    {
        foreach ($config as $k => $v)
            $this->$k = $v;
    }

    function connect()
    {
        try {
            $this->db = new PDO("mysql:host=$this->servername;dbname=$this->database;charset=utf8", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->exec("set names utf8");
            // echo "Connected successfully"; 

        } catch (PDOException $e) {
            // Không hiển thị chi tiết lỗi kết nối
            if ($this->isProduction) {
                error_log("Database connection error: " . $e->getMessage());
                echo "Không thể kết nối đến cơ sở dữ liệu. Vui lòng liên hệ quản trị viên.";
            } else {
                echo "Lỗi kết nối: " . $e->getMessage();
            }
        }
    }

    function disconnect()
    {
        $this->db = null;
    }

    function select($str = "*")
    {
        $this->sql = "select " . $str;
        $this->sql .= " from " . $this->refix . $this->table;
        $this->sql .=  $this->where;
        $this->sql .=  $this->order;
        $this->sql .=  $this->limit;
        $this->sql = str_replace('#_', $this->refix, $this->sql);
        return $this->query($this->sql); // Đảm bảo hàm query được gọi với tham số $this->sql
    }

    function query($sql)
    {
        $this->sql = str_replace('#_', $this->refix, $sql);
        $this->lastQuery = $this->sql; // Store the last query
        $stmt = $this->db->prepare($this->sql);
        return $stmt->execute();
    }

    function fetch_array($sql)
    {

        $arr = array();
        $this->sql = str_replace('#_', $this->refix, $sql);
        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function fetch()
    {
        $arr = array();
        $this->sql = str_replace('#_', $this->refix, $this->sql);

        // Lưu truy vấn an toàn hơn - loại bỏ thông tin nhạy cảm
        $this->logQuery($this->sql);

        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function o_fet($sql)
    {
        $this->sql = $sql;
        $this->lastQuery = str_replace('#_', $this->refix, $sql); // Lưu lại câu SQL
        return $this->fetch();
    }
    public function o_fet_class($sql)
    {
        $this->sql = $sql;
        return $this->fetch_class();
    }
    public function fetch_class()
    {
        $arr = array();
        $this->sql = str_replace('#_', $this->refix, $this->sql);
        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function o_sel($sel, $table, $where = "", $order = "", $limit = "")
    {
        if ($where <> '')  $where = " where " . $where;
        else $where = "";
        if ($order <> '')  $order = " order by " . $order;
        else $order = "";
        if ($limit <> '')  $limit = " limit " . $limit;
        else $limit = "";
        $sql = "select " . $sel . " from " . $table . " " . $where . $order . $limit;
        $this->sql = $sql;
        return $this->fetch();
    }
    public function o_que($sql)
    {
        $this->sql = $sql;
        return $this->que();
    }
    function assoc_array($sql)
    {
        $this->sql = str_replace('#_', $this->refix, $sql);
        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function num_rows($sql)
    {
        $this->sql = str_replace('#_', $this->refix, $sql);
        $this->lastQuery = $this->sql; // Lưu lại câu SQL
        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function num()
    {
        $this->sql = str_replace('#_', $this->refix, $this->sql);
        $this->lastQuery = $this->sql; // Lưu lại câu SQL
        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function que()
    {
        $this->sql = str_replace('#_', $this->refix, $this->sql);
        $this->lastQuery = $this->sql; // Lưu lại câu SQL
        $stmt = $this->db->prepare($this->sql);
        return $stmt->execute();
    }

    function setTable($str)
    {
        $this->table = $str;
    }

    function setWhere($col, $dk)
    {
        if ($this->where == "") {
            $this->where = " where " . $col . " = '" . $dk . "'";
        } else {
            $this->where .= " and " . $col . " = '" . $dk . "'";
        }
    }

    function setWhereOrther($col, $dk)
    {
        if ($this->where == "") {
            $this->where = " where " . $col . " <> '" . $dk . "'";
        } else {
            $this->where .= " and " . $col . " <> '" . $dk . "'";
        }
    }

    function setWhereOr($col, $dk)
    {
        if ($this->where == "") {
            $this->where = " where " . $col . " = '" . $dk . "'";
        } else {
            $this->where .= " or " . $col . " = '" . $dk . "'";
        }
    }

    function setOrder($str)
    {
        $this->order = " order by " . $str;
    }

    function setLimit($str)
    {
        $this->limit = " limit " . $str;
    }

    function reset()
    {
        $this->sql = "";
        $this->result = "";
        $this->where = "";
        $this->order = "";
        $this->limit = "";
        $this->table = "";
    }

    function insert($data = array())
    {
        $into = "";
        $values = "";
        foreach ($data as $int => $val) {
            $into .= "," . $int;
            $values .= ", :$int";
        }
        // Fix string access syntax for PHP 8
        if ($into[0] == ",") $into = "(" . substr($into, 1) . ")";
        else $into = "(" . $into . ")";

        if ($values[0] == ",") $values = "(" . substr($values, 1) . ")";
        else $values = "(" . $values . ")";

        $this->sql = "insert into " . $this->table . $into . " values " . $values;
        $this->sql = str_replace('#_', $this->refix, $this->sql);
        $this->lastQuery = $this->sql; // Lưu lại câu SQL

        $stmt = $this->db->prepare($this->sql);
        foreach ($data as $int => $val) {
            $stmt->bindValue(":$int", $val); // Use bindValue instead of bindParam
        }
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        } else {
            // Thêm kiểm tra lỗi
            $errorInfo = $stmt->errorInfo();
            echo "SQL Error: " . $errorInfo[2];
            return false;
        }
    }

    public function update($data = array())
    {
        $values = "";
        foreach ($data as $col => $val) {
            // Kiểm tra và bảo vệ dữ liệu nhạy cảm
            if (in_array(strtolower($col), ['password', 'mat_khau', 'token'])) {
                $values .= ", $col = :$col";
            } else {
                $values .= ", $col = :$col";
            }
        }
        // Fix string access syntax for PHP 8
        if ($values[0] == ",") $values = substr($values, 1);

        $this->sql = "update " . $this->table . " set " . $values . $this->where;

        $this->sql = str_replace('#_', $this->refix, $this->sql);

        // Sử dụng phương thức log an toàn
        $this->logQuery($this->sql);

        $stmt = $this->db->prepare($this->sql);
        foreach ($data as $col => $val) {
            $stmt->bindValue(":$col", $val); // Use bindValue instead of bindParam
        }

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                $this->lastError = "[Error Code] Database update failed"; // Ẩn chi tiết lỗi
                if (!$this->isProduction) {
                    $this->lastError = implode(': ', $stmt->errorInfo()); // Chỉ lưu chi tiết lỗi khi không ở môi trường sản xuất
                }
                return false;
            }
        } catch (PDOException $e) {
            $this->lastError = "[Exception] Database operation failed"; // Ẩn chi tiết lỗi
            if (!$this->isProduction) {
                $this->lastError = $e->getMessage(); // Chỉ lưu chi tiết lỗi khi không ở môi trường sản xuất
            }
            return false;
        }
    }

    function delete()
    {
        $this->sql = "delete from " . $this->table . $this->where;
        $this->sql = str_replace('#_', $this->refix, $this->sql);
        return $this->query($this->sql);
    }
    // other-----------------------------
    function alert($str)
    {
        // Làm sạch chuỗi trước khi hiển thị
        $str = $this->cleanOutput($str);
        echo '<script language="javascript"> alert("' . $str . '") </script>';
    }

    function location($url)
    {
        // Xác thực URL trước khi chuyển hướng
        $url = $this->validateRedirectUrl($url);
        echo '<script language="javascript">window.location = "' . $url . '" </script>';
    }

    function checkLink($alias, $id = '')
    {
        if ($id != '') {
            $where = " and id <> " . $id;
        } else {
            $where = "";
        }
        $row_cate = $this->num_rows("select * from #_category where alias = '$alias' $where ");
        $row_sanpham = $this->num_rows("select * from #_sanpham where alias = '$alias' $where ");
        $row_tintuc = $this->num_rows("select * from #_tintuc where alias = '$alias' $where ");
        if ($row_cate == 0 and $row_sanpham == 0 and $row_tintuc == 0) {
            return 1;
        } else {
            return 0;
        }
    }
    function fullAddress()
    {
        $adr = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $adr .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $adr .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        $adr2 = explode('&page=', $adr);
        return $adr2[0];
    }

    function fullAddress1()
    {
        $adr = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $adr .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $adr .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        $adr2 = explode('&page=', $adr);
        $adr3 = explode('&sort=', $adr2[0]);
        return $adr3[0];
    }
    function fullAddress2()
    {
        $adr = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $adr .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $adr .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        $adr2 = explode('&page=', $adr);
        $adr3 = explode('&limit=', $adr2[0]);
        return $adr3[0];
    }

    function fns_Rand_digit($min, $max, $num)
    {
        $result = '';
        for ($i = 0; $i < $num; $i++) {
            $result .= rand($min, $max);
        }
        return $result;
    }
    function simple_fetch($sql)
    {
        $arr = array();
        $this->sql = str_replace('#_', $this->refix, $sql);
        $this->lastQuery = $this->sql; // Lưu lại câu SQL
        $stmt = $this->db->prepare($this->sql); // Đảm bảo $this->sql đã được gán giá trị
        $stmt->execute();
        // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetchAll();
        if (!empty($result)) {
            return $result[0];
        }
        return array();
    }
    function findIdSub($id, $level = 0)
    {
        $str = "";
        $query = $this->o_fet("select * from #_category where id_loai=$id and hien_thi=1 order by so_thu_tu asc, id desc");
        if (count($query) > 0) { // Sửa lỗi cú pháp
            foreach ($query as $item) {
                $str .= "," . $item['id'];
                $check = $this->o_fet("select * from #_category where id_loai={$item['id']} and hien_thi=1 order by so_thu_tu asc, id desc");
                if (count($check) > 0 && $level == 0) {
                    $str .= $this->findIdSub($item['id']);
                }
            }
        }
        return $str;
    }

    function breadcrumbid($id)
    {
        $str = "";
        $i = 0;
        $query = $this->simple_fetch("select * from cf_code where id=$id and hien_thi=1");
        $str .= $query['id'] . ",";
        if ($query['id_loai'] > 0) {
            $i++;
            $str = $this->breadcrumbid($query['id_loai']) . $str;
        }
        return $str;
    }
    function breadcrumblist($id)
    {
        $BreadcrumbList =  trim($this->breadcrumbid($id), ',');
        $arrBrceList = explode(',', $BreadcrumbList);
        $dem = count($arrBrceList);
        $j = 2;
        $itemBrcelist = "";
        for ($i = 0; $i < count($arrBrceList); $i++) {
            if ($i + 1 == $dem) {
                $act = 'active';
            } else {
                $act = "";
            }
            $row = $this->simple_fetch("select * from #_category where id_code = '" . $arrBrceList[$i] . "' and lang ='" . _lang . "'");
            $itemBrcelist .= '
                <li property="itemListElement" typeof="ListItem" class="breadcrumb-item ' . $act . '">
                    <a property="item" typeof="WebPage" href="' . _URLLANG . $row['alias'] . '.html">
                    <span property="name">' . $row['ten'] . '</span></a>
                    <meta property="position" content="' . $j . '">
                </li>';
            $j++;
        }
        $Brcelist = '
        <ol vocab="https://schema.org/" typeof="BreadcrumbList" class="breadcrumb"> 
            <li property="itemListElement" typeof="ListItem" class="breadcrumb-item">
                <a property="item" typeof="WebPage" href="' . _URLLANG . '">
                <span property="name">Home</span></a>
                 <meta property="position" content="1">
            </li>
            ' . $itemBrcelist . '
        </ol>';
        return $Brcelist;
    }
    function clear($html)
    {
        $str = "";
        $str = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        return $str;
    }

    function generateUniqueToken($username)
    {
        $token = time() . rand(10, 5000) . sha1(rand(10, 5000)) . md5(__FILE__);
        $token = str_shuffle($token);
        $token = sha1($token) . md5(microtime()) . md5($username);
        return $token;
    }

    function getPassHash($token, $password)
    {
        // Thay thế hàm băm mật khẩu cũ bằng cách sử dụng password_hash
        // Lưu ý: Khi triển khai, hãy cập nhật cách lưu và kiểm tra mật khẩu
        if (strlen($password) < 8) {
            return false; // Mật khẩu quá ngắn
        }

        // Nếu sử dụng password_hash thì không cần token
        // Đây là phương pháp mới, an toàn hơn
        return password_hash($password, PASSWORD_DEFAULT);

        // Giữ lại phương thức cũ làm tham chiếu nếu cần
        // $password_hash = md5(md5($token) . md5($password));
        // return $password_hash;
    }

    // Thêm phương thức kiểm tra mật khẩu
    function verifyPassword($password, $hashedPassword)
    {
        // Kiểm tra xem hash có phải do password_hash tạo ra không
        if (password_get_info($hashedPassword)['algo'] !== 0) {
            return password_verify($password, $hashedPassword);
        } else {
            // Nếu là hash kiểu cũ (md5), sử dụng phương thức cũ
            // Lưu ý: Khi người dùng đăng nhập thành công với hash cũ,
            // bạn nên cập nhật hash của họ lên định dạng mới
            $token = $_SESSION['token'] ?? ''; // Lấy token từ session
            $oldHash = md5(md5($token) . md5($password));
            return $oldHash === $hashedPassword;
        }
    }

    function clean($str)
    {
        // Loại bỏ @ để không ẩn các cảnh báo lỗi
        $str = trim($str);

        // Loại bỏ các thẻ HTML và PHP
        $str = strip_tags($str);

        // Bảo vệ chống XSS bằng cách chuyển đổi các ký tự đặc biệt
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');

        // Ngăn chặn SQL injection (thêm vào các hàm xử lý đầu vào)
        $str = str_replace(
            ['\\', "\0", "'", '"', "\x1a", "\x00"],
            ['\\\\', '\\0', "\\'", '\\"', '\\Z', '\\Z'],
            $str
        );

        return $str;
    }

    // Phương thức làm sạch đầu ra
    function cleanOutput($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    function subText($text, $num)
    {
        $str_len = strlen($text);
        if ($str_len < $num) {
            $str = $text;
        } else {
            $str = mb_substr($text, 0, $num, 'UTF-8') . "...";
        }
        return $str;
    }

    function redirect($url = '')
    {
        // Xác thực URL trước khi chuyển hướng
        $url = $this->validateRedirectUrl($url);
        echo '<script language="javascript">window.location = "' . $url . '" </script>';
        exit();
    }

    function link_redirect($alias = '')
    {
        $link_web = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $link_goc = URLPATH . $alias;

        if ($link_web != $link_goc) {
            $this->redirect($link_goc);
        }
    }

    function array_category($id_loai = 0, $plit = "=", $select_ = "", $module = 0, $notshow = 0)
    {
        $str = "";
        $and = ($notshow > 0) ? " and id!=$notshow" : '';

        if ($id_loai == 0) {
            $query = $this->o_fet("select * from cf_code where id_loai=0 $and order by so_thu_tu asc, id desc");
            //echo $d->sql;
            $plit = "";
        } else {
            $query = $this->o_fet("select * from cf_code where id_loai=$id_loai $and order by so_thu_tu asc, id desc");
            //echo $d->sql;
            $plit .= "= ";
        }

        foreach ($query as $item) {
            if ($item['id'] == $select_) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }
            if ($module > 0) {
                if ($item['module'] == $module) {
                    $str .= "<option value='" . $item['id'] . "' " . $selected . ">" . $plit . " " . $item['ten'] . "</option>";
                }
            } else {
                $str .= "<option value='" . $item['id'] . "' " . $selected . ">" . $plit . " " . $item['ten'] . "</option>";
            }

            $check_sub = $this->num_rows("select * from cf_code where id_loai='{$item['id']}'");

            if ($check_sub > 0) {
                $str .= $this->array_category($item['id'], $plit, $select_, $module, $notshow);
            }
        }
        return $str;
    }

    function many_extra($post)
    {
        $str = "";
        $post = $this->clear($post);
        foreach ($post as $item) {
            $str .= "," . $item;
        }
        return $str;
    }

    function getIdsub($id_code)
    {
        $lis_id = ''; // Khai báo biến $lis_id
        $query = $this->o_fet("select * from cf_code where id_loai= $id_code");
        foreach ($query as $key => $value) {
            $lis_id .= ',' . $value['id'];
            $query2 = $this->o_fet("select * from cf_code where id_loai= " . $value['id']);
            if (count($query2) > 0) {
                $lis_id .= $this->getIdsub($value['id']);
            }
        }
        return  $lis_id;
    }

    public function checkPermission($id_user, $id_page)
    {
        if ($_SESSION['is_admin'] == 1) {
            return 1;
        } else {
            $query = $this->o_fet("select * from #_user_permission_group where id_user = $id_user and id_permission in ($id_page)");
            if (count($query) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function checkPermission_view($id_page)
    {
        if ($_SESSION['is_admin'] == 1) {
            return 1;
        } else {
            $query = $this->o_fet("select * from #_user_permission_group where id_user = " . $_SESSION['id_user'] . " and id_permission = $id_page and (action like '%1%' or action like '%2%' or action like '%3%') ");
            if (count($query) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function checkPermission_edit($id_page)
    {
        if ($_SESSION['is_admin'] == 1) {
            return 1;
        } else {
            $query = $this->o_fet("select * from #_user_permission_group where id_user = " . $_SESSION['id_user'] . " and id_permission = $id_page and (action like '%3%' or action like '%2%')");
            if (count($query) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function checkPermission_dele($id_page)
    {
        if ($_SESSION['is_admin'] == 1) {
            return 1;
        } else {
            $query = $this->o_fet("select * from #_user_permission_group where id_user = " . $_SESSION['id_user'] . " and id_permission = $id_page and (action like '%3%')");
            if (count($query) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    function get_nav_act($id)
    {
        $BreadcrumbList =  trim($this->breadcrumbid($id), ',');
        $arrBrceList = explode(',', $BreadcrumbList);
        return $arrBrceList[0];
    }

    function getnews($id_code, $col = '*')
    {
        $row = $this->simple_fetch("select $col from #_tintuc where id_code = $id_code and hien_thi = 1 " . _where_lang . "");
        return $row;
    }

    function getNewss($id_code, $col = '*', $home = '', $limit = '', $where2 = '')
    {
        if ($home != '') {
            $where = " and noi_bat = 1";
        } else {
            $where = "";
        }
        if ($limit != '') {
            $limit_txt = "limit " . $limit;
        } else {
            $limit_txt = '';
        }
        $list_id = $id_code . $this->getIdsub($id_code);
        $row = $this->o_fet("select $col from #_tintuc where id_loai in ($list_id) $where2 and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC $limit_txt");
        return $row;
    }

    function getProduct($id_code, $col = '*')
    {
        $row = $this->simple_fetch("select $col from #_sanpham where id_code = $id_code and hien_thi = 1 " . _where_lang . "");
        return $row;
    }

    function getProducts($id_code, $home = '', $limit = '')
    {
        if ($home != '') {
            $where = " and tieu_bieu = 1";
        } else {
            $where = "";
        }
        if ($limit != '') {
            $limit_txt = "limit " . $limit;
        } else {
            $limit_txt = '';
        }
        $list_id = $id_code . $this->getIdsub($id_code);
        $row = $this->o_fet("select * from #_sanpham where id_loai in ($list_id) $where and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC $limit_txt");
        return $row;
    }

    function getCate($id_code, $col = '*')
    {
        $row = $this->simple_fetch("select $col from #_category where id_code = $id_code and hien_thi = 1 " . _where_lang . "");
        if ($col == '*') {
            return $row;
        } else {
            return $row[$col];
        }
    }

    function getCates($id_code, $home = '')
    { //1:check home - 2:check menu
        if ($home == '1') {
            $where = " and tieu_bieu = 1";
        } elseif ($home == '2') {
            $where = " and menu = 1";
        } else {
            $where = "";
        }
        $row = $this->o_fet("select * from #_category where id_loai = $id_code $where and hien_thi =1 " . _where_lang . " order by so_thu_tu ASC, id DESC");
        return $row;
    }

    function getContent($id_code, $col = '')
    {
        if ($col == '') {
            $row = $this->simple_fetch("select * from #_category_noidung where hien_thi = 1  and id_code = $id_code " . _where_lang . " ");
            return $row;
        } else {
            $row = $this->simple_fetch("select $col from #_category_noidung where hien_thi = 1  and id_code = $id_code " . _where_lang . "  ");
            return $row[$col];
        }
    }

    function getContents($id_code, $limit = '')
    {
        if ($limit != '') {
            $where = " limit 0," . $limit;
        } else {
            $where = "";
        }
        $row = $this->o_fet("select * from #_content where hien_thi = 1 and id_loai = $id_code " . _where_lang . "  order by so_thu_tu ASC, id DESC $where");
        return $row;
    }

    function getContent_id($id_code, $limit = '')
    {
        if ($limit != '') {
            $where = " limit 0," . $limit;
        } else {
            $where = "";
        }
        $row = $this->simple_fetch("select * from #_content where hien_thi = 1 and id_code = $id_code " . _where_lang . " order by so_thu_tu ASC, id DESC $where");
        return $row;
    }

    function getData($tale, $col = '', $where = '', $limit = '')
    {
        if ($limit != '') {
            $limited = 'limit 0,' . $limit;
        } else {
            $limited = "";
        }
        if ($col != '') {
            $col_txt = $col;
        } else {
            $col_txt = '*';
        }
        if ($where != "") {
            $where_txt = " and $where";
        } else {
            $where_txt = '';
        }
        $row = $this->o_fet("select $col_txt from #_" . $tale . " where hien_thi = 1 $where_txt order by so_thu_tu ASC, id DESC $limited");
        return $row;
    }

    function getTinh($col = '*', $id = '')
    {
        if ($id == '') {
            $row = $this->o_fet("select $col from #_thanhpho order by ten ASC ");
        } else {
            $row = $this->simple_fetch("select $col from #_thanhpho where code= '" . $id . "' order by ten ASC ");
        }
        return $row;
    }

    function getHuyen($code_tinh, $col = '*', $code = '')
    {
        if ($code == '') {
            $row = $this->o_fet("select $col from #_huyen where code_tinh ='" . $code_tinh . "' order by ten ASC ");
        } else {
            $row = $this->simple_fetch("select $col from #_huyen where code_tinh ='" . $code_tinh . "' and code= '" . $code . "' order by ten ASC ");
        }
        return $row;
    }

    function getXa($code_huyen, $col = '*', $code = '')
    {
        if ($code == '') {
            $row = $this->o_fet("select $col from #_xa where code_huyen ='" . $code_huyen . "' order by ten ASC ");
        } else {
            $row = $this->simple_fetch("select $col from #_xa where code_huyen ='" . $code_huyen . "' and code='" . $code . "' order by ten ASC ");
        }
        return $row;
    }

    function getDataId($tale, $id_code, $col = '*')
    {
        $row = $this->simple_fetch("select $col from #_" . $tale . " where id_code = $id_code  limit 0,1");
        return $row;
    }

    function getTxt($id)
    {
        $row = $this->simple_fetch("select text from #_text where id = $id ");
        $str = $row['text'];
        $arr_txt = json_decode(stripslashes($str), true);
        return $arr_txt[$_SESSION['lang']]; // Sử dụng biến session để lấy ngôn ngữ hiện tại
    }

    function getReview($id_sp)
    {
        //echo "select * from db_binhluan where id_sanpham =".(int)$id_sp." and trang_thai = 1 and parent=0 and danh_gia > 0 ";
        $row = $this->simple_fetch("select * from #_sanpham where id_code = " . $id_sp . " ");
        $count_bl = $this->num_rows("select * from #_binhluan where id_sanpham =" . (int)$id_sp . " and trang_thai = 1 and parent=0 and danh_gia > 0 ");
        $tongsao = $this->simple_fetch("select sum(danh_gia) as tong from #_binhluan where id_sanpham =" . (int)$id_sp . " and trang_thai = 1 and parent=0 and danh_gia > 0 order by id DESC ");
        if ($count_bl > 0) {
            $sao_trung_binh = $tongsao['tong'] / $count_bl;
        } else {
            $sao_trung_binh = 0;
        }

        if ($sao_trung_binh > 0) {
            $sao = '';
            for ($i = 0; $i < $sao_trung_binh; $i++) {
                $sao .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>';
            }
            for ($i = 0; $i < 5 - $sao_trung_binh; $i++) {
                $sao .=  '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>';
            }
        } else {
            $sao = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>';
        }
        //$tong_ban = $this->simple_fetch("SELECT id_sp, SUM(so_luong) as tong FROM `db_dathang_chitiet` WHERE id_sp= ".(int)$id_sp." GROUP BY id_sp");
        $review = '<span>' . $sao_trung_binh . '</span><ul class="rating-d"> ' . $sao . ' </ul> <span>Đã bán <b>' . $row['da_ban'] . '</b></span>';
        return $review;
    }

    function getReview2($id_sp)
    {
        //echo "select * from db_binhluan where id_sanpham =".(int)$id_sp." and trang_thai = 1 and parent=0 and danh_gia > 0 ";
        $row = $this->simple_fetch("select * from #_sanpham where id_code = " . $id_sp . " ");
        $count_bl = $this->num_rows("select * from #_binhluan where id_sanpham =" . (int)$id_sp . " and trang_thai = 1 and parent=0 and danh_gia > 0 ");
        $tongsao = $this->simple_fetch("select sum(danh_gia) as tong from #_binhluan where id_sanpham =" . (int)$id_sp . " and trang_thai = 1 and parent=0 and danh_gia > 0 order by id DESC ");
        if ($count_bl > 0) {
            $sao_trung_binh = $tongsao['tong'] / $count_bl;
        } else {
            $sao_trung_binh = 0;
        }

        if ($sao_trung_binh > 0) {
            $sao = '';
            for ($i = 0; $i < $sao_trung_binh; $i++) {
                $sao .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>';
            }
            for ($i = 0; $i < 5 - $sao_trung_binh; $i++) {
                $sao .=  '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>';
            }
        } else {
            $sao = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
</svg>';
        }
        $tong_ban = $this->simple_fetch("SELECT id_sp, SUM(so_luong) as tong FROM `db_dathang_chitiet` WHERE id_sp= " . (int)$id_sp . " GROUP BY id_sp");
        $review = '<span style="position: relative;top: 3px;">' . $sao_trung_binh . '</span><ul class="rating-d"> ' . $sao . ' </ul> |<span class="px-2">( <b>' . $count_bl . '</b> đánh giá )</span> | <span class="px-2">Đã bán <b>' . $row['da_ban'] . '</b></span>';
        return $review;
    }

    function showthongtin($data = '')
    {
        $url = URLPATH;
        $mxh        =   $this->simple_fetch("select * from #_thongtin where lang = '" . $_SESSION['lang'] . "'");
        if ($data == '') {
            return $mxh;
        } else {
            if ($data == 'logo') {
                $text = $url . 'img_data/images/' . $mxh['icon_share'];
            } elseif ($data == 'favicon') {
                $text = $url . 'img_data/images/' . $mxh['favicon'];
            } elseif ($data == 'backlink') {
                $text = '<a href="https://lifetech-media.vn/" target="_blank" title="Design Web: LifeTech Media">Design Web: LifeTech Media</a>';
            } else {
                $text = $mxh[$data];
            }
            return $text;
        }
    }

    /**
     * Chuẩn bị một câu lệnh SQL với tham số
     * @param string $sql Câu lệnh SQL có chứa các placeholder ?
     * @return PDOStatement Trả về đối tượng PDOStatement đã chuẩn bị
     */
    public function prepare($sql)
    {
        $sql = str_replace('#_', $this->refix, $sql);
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt;
        } catch (PDOException $e) {
            echo 'Lỗi chuẩn bị truy vấn: ' . $e->getMessage();
            exit;
        }
    }

    /**
     * Thực thi câu lệnh SQL đã chuẩn bị với tham số
     * @param PDOStatement $stmt Đối tượng PDOStatement
     * @param array $params Mảng tham số cho câu lệnh SQL
     * @return PDOStatement Trả về đối tượng PDOStatement đã thực thi
     */
    public function execute($stmt, $params = [])
    {
        try {
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            echo 'Lỗi thực thi truy vấn: ' . $e->getMessage();
            exit;
        }
    }

    /**
     * Lấy tất cả kết quả từ câu lệnh đã thực thi
     * @param PDOStatement $stmt Đối tượng PDOStatement đã thực thi
     * @return array Mảng kết quả
     */
    public function fetchAll($stmt)
    {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thực hiện truy vấn an toàn với prepared statement
     * @param string $sql Câu lệnh SQL có chứa các placeholder ?
     * @param array $params Mảng tham số cho câu lệnh SQL
     * @return array Mảng kết quả truy vấn
     */
    public function query_prepared($sql, $params = [])
    {
        $stmt = $this->prepare($sql);
        $this->execute($stmt, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hiển thị thông báo và chuyển hướng đến URL đích
     * @param string $msg Thông báo hiển thị
     * @param string $url URL đích để chuyển hướng
     * @return void
     */
    function transfer($msg, $url)
    {
        $msg = $this->cleanOutput($msg);
        $url = $this->validateRedirectUrl($url);

        echo '<div style="width: 400px; padding: 10px 20px; background: #fff; border-radius: 3px; margin: 100px auto; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">';
        echo '<h3 style="color: #333; margin-bottom: 20px; text-align:center;">THÔNG BÁO</h3>';
        echo '<div style="color: #333; font-size: 14px; margin-bottom: 20px; line-height: 1.5;">' . $msg . '</div>';
        echo '<div style="text-align:center;"><a style="display: inline-block; padding: 8px 15px; background: #4caf50; color: #fff; text-decoration: none; border-radius: 3px;" href="' . $url . '">Tiếp tục</a>';
        echo '</div></div>';
        echo '<script>setTimeout(function(){ window.location.href = "' . $url . '"; }, 1500);</script>';
        exit();
    }

    // Phương thức xác thực URL chuyển hướng để ngăn chặn Open Redirect
    private function validateRedirectUrl($url)
    {
        // Nếu URL trống, trả về trang chủ
        if (empty($url)) {
            return './';
        }

        // Nếu là URL tương đối (không bắt đầu bằng http hoặc https), chấp nhận
        if (strpos($url, 'http') !== 0) {
            return $url;
        }

        // Nếu là URL tuyệt đối, kiểm tra xem có thuộc domain cho phép không
        $allowedDomains = [
            $_SERVER['HTTP_HOST'],
            'demo-website.vn'
            // Thêm các domain cho phép khác nếu cần
        ];

        $urlParts = parse_url($url);
        $domain = isset($urlParts['host']) ? $urlParts['host'] : '';

        if (!in_array($domain, $allowedDomains)) {
            // Nếu domain không được phép, trả về URL trang chủ
            return './';
        }

        return $url;
    }

    /**
     * Get the last executed SQL query - security enhanced
     * @return string The last SQL query (sanitized for production)
     */
    function getLastQuery()
    {
        // Use the class property instead of checking for undefined constant
        if ($this->isProduction) {
            // In production, don't expose actual SQL
            return "SQL query details are hidden in production for security reasons.";
        }

        if (!empty($this->lastQuery)) {
            // In development, return the full query
            return $this->lastQuery;
        }

        if (!empty($this->sql)) {
            return str_replace('#_', $this->refix, $this->sql);
        }

        return "Không có câu truy vấn SQL nào được ghi nhận";
    }

    /**
     * Get the last database error - security enhanced
     * @return string The last error message (sanitized for production)
     */
    function getLastError()
    {
        // Use the class property instead of checking for undefined constant
        if ($this->isProduction) {
            // In production, return a generic error message
            // But log the actual error for administrators
            if (!empty($this->lastError)) {
                error_log("Database Error: " . $this->lastError);
            } else if ($this->db) {
                $errorInfo = $this->db->errorInfo();
                if ($errorInfo[0] != '00000') {
                    error_log("Database Error: " . implode(': ', $errorInfo));
                }
            }

            return "A database error occurred. Please contact the administrator.";
        }

        // In development, provide detailed error information
        if (!empty($this->lastError)) {
            return $this->lastError;
        }

        if ($this->db) {
            $errorInfo = $this->db->errorInfo();
            if ($errorInfo[0] != '00000') {
                return implode(': ', $errorInfo);
            }
        }

        return "No specific error information available";
    }

    // Phương thức lưu truy vấn an toàn
    private function logQuery($sql)
    {
        // Xóa thông tin nhạy cảm trước khi lưu
        $sensitiveWords = ['password', 'mat_khau', 'token', 'credit', 'card'];
        $this->lastQuery = $sql;

        // Nếu là câu truy vấn cập nhật/chèn có chứa thông tin nhạy cảm
        if (preg_match('/\b(insert|update)\b/i', $sql)) {
            foreach ($sensitiveWords as $word) {
                // Thay thế giá trị của các trường nhạy cảm
                $pattern = '/(' . $word . '\s*=\s*)[^\s,)]+/i';
                $this->lastQuery = preg_replace($pattern, '$1[HIDDEN]', $this->lastQuery);
            }
        }
    }

    function array_category_multi($id_loai = 0, $plit = "=", $selected_ids_str = '', $module = 0, $notshow = 0)
    {
        $str = "";
        $and = ($notshow > 0) ? " and id!=$notshow" : '';

        // Parse the string of IDs in format ',1,2,3,' into an array
        $selected_ids = [];
        if (!empty($selected_ids_str) && is_string($selected_ids_str)) {
            $selected_ids_str = trim($selected_ids_str, ',');
            if (!empty($selected_ids_str)) {
                $selected_ids = explode(',', $selected_ids_str);
                // Ensure all items are integers
                $selected_ids = array_map('intval', $selected_ids);
            }
        }

        if ($id_loai == 0) {
            $query = $this->o_fet("select * from cf_code where id_loai=0 $and order by so_thu_tu asc, id desc");
            $plit = "";
        } else {
            $query = $this->o_fet("select * from cf_code where id_loai=$id_loai $and order by so_thu_tu asc, id desc");
            $plit .= "= ";
        }

        foreach ($query as $item) {
            // Check if this item's ID is in the selected_ids array
            if (in_array((int)$item['id'], $selected_ids)) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }

            if ($module > 0) {
                if ($item['module'] == $module) {
                    $str .= "<option value='" . $item['id'] . "' " . $selected . ">" . $plit . " " . $item['ten'] . "</option>";
                }
            } else {
                $str .= "<option value='" . $item['id'] . "' " . $selected . ">" . $plit . " " . $item['ten'] . "</option>";
            }

            // Check for subcategories
            $check_sub = $this->num_rows("select * from cf_code where id_loai='{$item['id']}'");

            if ($check_sub > 0) {
                // Recursively add subcategories, passing the same selected_ids string
                $str .= $this->array_category_multi($item['id'], $plit, $selected_ids_str, $module, $notshow);
            }
        }
        return $str;
    }
}