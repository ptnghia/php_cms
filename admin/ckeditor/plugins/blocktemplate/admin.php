<?php
// Thiết lập header
header('Content-Type: text/html; charset=utf-8');
session_start();

// Enhanced security checks
if (!isset($_SESSION["user_hash"])) {
    die('Access denied. Please login.');
}

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Thông tin phiên làm việc
$currentTime = date('Y-m-d H:i:s');
$currentUser = htmlspecialchars($_SESSION['username'] ?? 'Unknown User');

// Security configuration
$maxFileSize = 2 * 1024 * 1024; // 2MB
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// Đường dẫn đến file JSON
$blocksFile = __DIR__ . '/blocks.json';

// Xử lý POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch. Access denied.');
    }
    
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    try {
        switch ($action) {
            case 'add_block':
                // Thêm block mới
                addBlock($_POST);
                break;

            case 'edit_block':
                // Sửa block
                editBlock($_POST['id'], $_POST);
                break;

            case 'delete_block':
                // Xóa block
                deleteBlock($_POST['id']);
                break;

            case 'add_category':
                // Thêm danh mục mới
                addCategory($_POST);
                break;
                
            default:
                throw new Exception('Invalid action');
        }

        // Chuyển hướng để tránh gửi lại form
        header('Location: ' . $_SERVER['PHP_SELF'] . '?updated=1');
        exit;
        
    } catch (Exception $e) {
        error_log('Block Template Error: ' . $e->getMessage());
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=' . urlencode('Có lỗi xảy ra: ' . $e->getMessage()));
        exit;
    }
}

// Đọc dữ liệu blocks
function readBlocksData()
{
    global $blocksFile;

    if (file_exists($blocksFile)) {
        $content = file_get_contents($blocksFile);
        return json_decode($content, true);
    }

    // Dữ liệu mặc định nếu file không tồn tại
    return [
        'categories' => [],
        'blocks' => []
    ];
}

// Lưu dữ liệu blocks
function saveBlocksData($data)
{
    global $blocksFile;

    // Thêm thông tin lần sửa đổi cuối
    $data['lastModified'] = [
        'time' => date('Y-m-d H:i:s'),
        'user' => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : 'unknown'
    ];

    $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($blocksFile, $jsonContent);
}

// Thêm block mới với enhanced validation
function addBlock($formData)
{
    global $maxFileSize, $allowedImageTypes, $allowedExtensions;
    
    // Validate input data
    validateBlockData($formData);
    
    $data = readBlocksData();

    // Xử lý upload thumbnail nếu có với security validation
    $thumbnailPath = 'images/blocks/default.png'; // Mặc định
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $thumbnailPath = handleImageUpload($_FILES['thumbnail']);
    }

    // Tạo ID duy nhất cho block
    $blockId = 'block_' . time() . '_' . rand(1000, 9999);

    // Sanitize input data
    $sanitizedData = sanitizeBlockData($formData);

    // Thêm block mới vào dữ liệu
    $data['blocks'][] = [
        'id' => $blockId,
        'name' => $sanitizedData['name'],
        'description' => $sanitizedData['description'],
        'category' => $sanitizedData['category'],
        'thumbnail' => $thumbnailPath,
        'code_html' => $sanitizedData['code_html'],
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $_SESSION['username'] ?? 'unknown'
    ];

    saveBlocksData($data);
}

// Enhanced validation và security functions đã chuyển xuống dưới

// Enhanced validation và security functions

// Validate block data
function validateBlockData($formData) {
    $errors = [];
    
    // Validate required fields
    if (empty(trim($formData['name']))) {
        $errors[] = 'Tên block không được để trống';
    }
    
    if (empty(trim($formData['description']))) {
        $errors[] = 'Mô tả không được để trống';
    }
    
    if (empty(trim($formData['category']))) {
        $errors[] = 'Danh mục không được để trống';
    }
    
    if (empty(trim($formData['code_html']))) {
        $errors[] = 'Code HTML không được để trống';
    }
    
    // Validate length limits
    if (strlen($formData['name']) > 255) {
        $errors[] = 'Tên block không được quá 255 ký tự';
    }
    
    if (strlen($formData['description']) > 500) {
        $errors[] = 'Mô tả không được quá 500 ký tự';
    }
    
    if (strlen($formData['code_html']) > 50000) {
        $errors[] = 'Code HTML không được quá 50KB';
    }
    
    // Check for potential XSS in name and description
    if (preg_match('/<script|javascript:|on\w+=/i', $formData['name'])) {
        $errors[] = 'Tên block chứa nội dung không an toàn';
    }
    
    if (preg_match('/<script|javascript:|on\w+=/i', $formData['description'])) {
        $errors[] = 'Mô tả chứa nội dung không an toàn';
    }
    
    if (!empty($errors)) {
        throw new Exception(implode('; ', $errors));
    }
}

// Sanitize block data
function sanitizeBlockData($formData) {
    return [
        'name' => htmlspecialchars(trim($formData['name']), ENT_QUOTES, 'UTF-8'),
        'description' => htmlspecialchars(trim($formData['description']), ENT_QUOTES, 'UTF-8'),
        'category' => preg_replace('/[^a-zA-Z0-9_-]/', '', trim($formData['category'])),
        'code_html' => trim($formData['code_html']) // HTML content - keep as is but validate separately
    ];
}

// Handle image upload with security validation
function handleImageUpload($file) {
    global $maxFileSize, $allowedImageTypes, $allowedExtensions;
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload failed with error code: ' . $file['error']);
    }
    
    // Check file size
    if ($file['size'] > $maxFileSize) {
        throw new Exception('File quá lớn. Kích thước tối đa là ' . ($maxFileSize / 1024 / 1024) . 'MB');
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedImageTypes)) {
        throw new Exception('Loại file không được phép. Chỉ chấp nhận: ' . implode(', ', $allowedImageTypes));
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        throw new Exception('Phần mở rộng file không được phép. Chỉ chấp nhận: ' . implode(', ', $allowedExtensions));
    }
    
    // Generate safe filename
    $safeFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', basename($file['name']));
    $targetDir = __DIR__ . '/images/blocks/';
    
    // Create directory if not exists
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            throw new Exception('Không thể tạo thư mục upload');
        }
    }
    
    $targetFile = $targetDir . $safeFileName;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        throw new Exception('Không thể lưu file upload');
    }
    
    return 'images/blocks/' . $safeFileName;
}

// Enhanced edit function
function editBlock($blockId, $formData)
{
    // Validate input data
    validateBlockData($formData);
    
    $data = readBlocksData();

    foreach ($data['blocks'] as $key => $block) {
        if ($block['id'] === $blockId) {
            // Xử lý upload thumbnail mới nếu có
            $thumbnailPath = $block['thumbnail']; // Giữ nguyên nếu không có upload mới
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
                $newThumbnailPath = handleImageUpload($_FILES['thumbnail']);
                
                // Xóa thumbnail cũ nếu không phải mặc định
                if ($thumbnailPath !== 'images/blocks/default.png' && file_exists(__DIR__ . '/' . $thumbnailPath)) {
                    unlink(__DIR__ . '/' . $thumbnailPath);
                }
                
                $thumbnailPath = $newThumbnailPath;
            }

            // Sanitize input data
            $sanitizedData = sanitizeBlockData($formData);

            // Cập nhật thông tin block
            $data['blocks'][$key] = [
                'id' => $blockId,
                'name' => $sanitizedData['name'],
                'description' => $sanitizedData['description'],
                'category' => $sanitizedData['category'],
                'thumbnail' => $thumbnailPath,
                'code_html' => $sanitizedData['code_html'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $_SESSION['username'] ?? 'unknown'
            ];

            break;
        }
    }

    saveBlocksData($data);
}

// Enhanced addCategory function
function addCategory($formData)
{
    // Validate category name
    if (empty(trim($formData['name']))) {
        throw new Exception('Tên danh mục không được để trống');
    }
    
    if (strlen($formData['name']) > 100) {
        throw new Exception('Tên danh mục không được quá 100 ký tự');
    }
    
    $data = readBlocksData();

    // Check for duplicate category names
    foreach ($data['categories'] as $category) {
        if (strtolower(trim($category['name'])) === strtolower(trim($formData['name']))) {
            throw new Exception('Tên danh mục đã tồn tại');
        }
    }

    // Tạo ID duy nhất cho danh mục
    $categoryId = 'cat_' . time() . '_' . rand(100, 999);

    // Thêm danh mục mới vào dữ liệu
    $data['categories'][] = [
        'id' => $categoryId,
        'name' => htmlspecialchars(trim($formData['name']), ENT_QUOTES, 'UTF-8'),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $_SESSION['username'] ?? 'unknown'
    ];

    saveBlocksData($data);
}

// Enhanced deleteBlock function
function deleteBlock($blockId)
{
    // Validate block ID
    if (empty($blockId) || !preg_match('/^block_\d+_\d+$/', $blockId)) {
        throw new Exception('Block ID không hợp lệ');
    }
    
    $data = readBlocksData();
    $blockFound = false;

    foreach ($data['blocks'] as $key => $block) {
        if ($block['id'] === $blockId) {
            $blockFound = true;
            
            // Xóa thumbnail nếu không phải mặc định
            if ($block['thumbnail'] !== 'images/blocks/default.png' && file_exists(__DIR__ . '/' . $block['thumbnail'])) {
                unlink(__DIR__ . '/' . $block['thumbnail']);
            }

            // Xóa block khỏi mảng
            array_splice($data['blocks'], $key, 1);
            break;
        }
    }
    
    if (!$blockFound) {
        throw new Exception('Không tìm thấy block cần xóa');
    }

    saveBlocksData($data);
}

// Lấy dữ liệu hiện tại
$blocksData = readBlocksData();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Block Template - CKEditor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <style>
        .container {
            max-width: 1200px;
        }

        .card {
            margin-bottom: 20px;
        }

        .thumbnail-preview {
            max-width: 100px;
            max-height: 60px;
        }

        .block-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .CodeMirror {
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Quản lý Block Template</h1>
            <div class="user-info text-right">
                <div><strong>Người dùng:</strong> <?php echo htmlspecialchars($currentUser); ?></div>
                <div><small><?php echo htmlspecialchars($currentTime); ?></small></div>
            </div>
        </div>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Cập nhật thành công!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách Block</h5>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addBlockModal">
                            <i class="fa fa-plus"></i> Thêm Block mới
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="block-list">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên</th>
                                        <th>Danh mục</th>
                                        <th>Mô tả</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($blocksData['blocks']) > 0): ?>
                                        <?php foreach ($blocksData['blocks'] as $block): ?>
                                            <tr>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($block['thumbnail']); ?>"
                                                        class="thumbnail-preview" alt="<?php echo htmlspecialchars($block['name']); ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($block['name']); ?></td>
                                                <td>
                                                    <?php
                                                    $categoryName = 'Không có';
                                                    foreach ($blocksData['categories'] as $category) {
                                                        if ($category['id'] === $block['category']) {
                                                            $categoryName = $category['name'];
                                                            break;
                                                        }
                                                    }
                                                    echo htmlspecialchars($categoryName);
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($block['description']); ?></td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-info edit-block-btn"
                                                        data-id="<?php echo htmlspecialchars($block['id']); ?>"
                                                        data-toggle="modal" data-target="#editBlockModal">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger delete-block-btn"
                                                        data-id="<?php echo htmlspecialchars($block['id']); ?>"
                                                        data-name="<?php echo htmlspecialchars($block['name']); ?>"
                                                        data-toggle="modal" data-target="#deleteBlockModal">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có block nào. Hãy thêm block mới!</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh mục</h5>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                            <i class="fa fa-plus"></i> Thêm danh mục
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($blocksData['categories']) > 0): ?>
                                    <?php foreach ($blocksData['categories'] as $category): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($category['id']); ?></td>
                                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2" class="text-center">Chưa có danh mục nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Thêm danh mục trước khi thêm block</li>
                            <li>Hình ảnh xem trước nên có tỷ lệ 16:9</li>
                            <li>Code HTML có thể chứa placeholders để người dùng tùy chỉnh</li>
                            <li>Tạo nhiều block cho mỗi danh mục để dễ tìm kiếm</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm Block mới -->
    <div class="modal fade" id="addBlockModal" tabindex="-1" role="dialog" aria-labelledby="addBlockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_block">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addBlockModalLabel">Thêm Block mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Tên Block</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Danh mục</label>
                            <select class="form-control" id="category" name="category" required>
                                <?php foreach ($blocksData['categories'] as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="thumbnail">Hình ảnh xem trước</label>
                            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="code_html">Code HTML</label>
                            <textarea class="form-control" id="code_html" name="code_html" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm Block</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal sửa Block -->
    <div class="modal fade" id="editBlockModal" tabindex="-1" role="dialog" aria-labelledby="editBlockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="editBlockForm">
                    <input type="hidden" name="action" value="edit_block">
                    <input type="hidden" name="id" id="edit_block_id">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editBlockModalLabel">Sửa Block</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Tên Block</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_category">Danh mục</label>
                            <select class="form-control" id="edit_category" name="category" required>
                                <?php foreach ($blocksData['categories'] as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Mô tả</label>
                            <input type="text" class="form-control" id="edit_description" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_code_html">Code HTML</label>
                            <textarea class="form-control" id="edit_code_html" name="code_html" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal xóa Block -->
    <div class="modal fade" id="deleteBlockModal" tabindex="-1" role="dialog" aria-labelledby="deleteBlockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="deleteBlockForm">
                    <input type="hidden" name="action" value="delete_block">
                    <input type="hidden" name="id" id="delete_block_id">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBlockModalLabel">Xác nhận xóa Block</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa block <span id="delete_block_name" class="font-weight-bold"></span>?</p>
                        <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal thêm Danh mục -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="action" value="add_category">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Thêm Danh mục mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category_name">Tên danh mục</label>
                            <input type="text" class="form-control" id="category_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/htmlmixed/htmlmixed.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CodeMirror for add block modal
            var addBlockEditor = CodeMirror.fromTextArea(document.getElementById('code_html'), {
                lineNumbers: true,
                mode: 'htmlmixed',
                theme: 'default'
            });

            // Initialize CodeMirror for edit block modal
            var editBlockEditor = CodeMirror.fromTextArea(document.getElementById('edit_code_html'), {
                lineNumbers: true,
                mode: 'htmlmixed',
                theme: 'default'
            });

            // Ensure CodeMirror content updates textareas before form submission
            $('#addBlockModal form').on('submit', function(e) {
                // Update textarea with current editor content
                addBlockEditor.save();

                // Simple validation
                if ($('#name').val().trim() === '' || $('#description').val().trim() === '' || addBlockEditor.getValue().trim() === '') {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc');
                    return false;
                }
            });

            $('#editBlockForm').on('submit', function(e) {
                // Update textarea with current editor content
                editBlockEditor.save();

                // Simple validation
                if ($('#edit_name').val().trim() === '' || $('#edit_description').val().trim() === '' || editBlockEditor.getValue().trim() === '') {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc');
                    return false;
                }
            });

            // Xử lý khi click vào nút sửa
            $('.edit-block-btn').click(function() {
                var blockId = $(this).data('id');

                // Tải dữ liệu block từ server
                $.getJSON('get_block.php', {
                    id: blockId
                }, function(block) {
                    $('#edit_block_id').val(block.id);
                    $('#edit_name').val(block.name);
                    $('#edit_category').val(block.category);
                    $('#edit_description').val(block.description);
                    editBlockEditor.setValue(block.code_html);

                    // Hiển thị hình ảnh hiện tại
                    $('#current_thumbnail').html('<img src="' + block.thumbnail + '" class="img-thumbnail" style="max-height: 100px">');
                }).fail(function() {
                    // Fallback nếu không có API, lấy dữ liệu từ biến JS
                    var blocks = <?php echo json_encode($blocksData['blocks']); ?>;
                    var block = blocks.find(function(b) {
                        return b.id === blockId;
                    });

                    if (block) {
                        $('#edit_block_id').val(block.id);
                        $('#edit_name').val(block.name);
                        $('#edit_category').val(block.category);
                        $('#edit_description').val(block.description);
                        editBlockEditor.setValue(block.code_html);

                        // Hiển thị hình ảnh hiện tại
                        $('#current_thumbnail').html('<img src="' + block.thumbnail + '" class="img-thumbnail" style="max-height: 100px">');
                    }
                });
            });

            // Xử lý khi click vào nút xóa
            $('.delete-block-btn').click(function() {
                var blockId = $(this).data('id');
                var blockName = $(this).data('name');

                $('#delete_block_id').val(blockId);
                $('#delete_block_name').text(blockName);
            });

            // Tự động đóng thông báo sau 5 giây
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>

</html>
