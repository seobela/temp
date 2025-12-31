<?php
/**
 * Professional PHP File Manager
 * A secure, single-file file management system
 * Version: 1.0
 */

// ============================================================================
// CONFIGURATION - CHANGE THESE VALUES
// ============================================================================

define('FM_ADMIN_USER', 'admin');
// Default password is 'admin' - CHANGE THIS IMMEDIATELY!
define('FM_ADMIN_PASS', '$2y$10$8K1p/a0dL3LKzBwKOCQ4rOW9.W4sJVNGUj8QrRb5QqhZv3lZLNKZG');
define('FM_SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('FM_ROOT_PATH', dirname(__FILE__)); // Start from script directory
define('FM_SHOW_HIDDEN', false); // Show hidden files (starting with .)
define('FM_ALLOWED_EXTENSIONS', 'txt,php,html,css,js,json,xml,htaccess,md,log,sql,csv,ini,conf,yml,yaml,hpp,cpp,c,h,py,java,rb,go,sh');
define('FM_MAX_UPLOAD_SIZE_MB', 50);
define('FM_ALLOW_SYSTEM_WIDE', true); // Allow navigation to any directory

// ============================================================================
// SECURITY HELPER CLASS
// ============================================================================

class SecurityHelper {
    
    public static function sanitizePath($path) {
        // Remove any null bytes
        $path = str_replace(chr(0), '', $path);
        
        // Normalize path separators
        $path = str_replace('\\', '/', $path);
        
        // Remove multiple slashes
        $path = preg_replace('#/+#', '/', $path);
        
        return $path;
    }
    
    public static function isPathSafe($path, $rootPath) {
        if (!FM_ALLOW_SYSTEM_WIDE) {
            $realPath = realpath($path);
            $realRoot = realpath($rootPath);
            
            if ($realPath === false || $realRoot === false) {
                return false;
            }
            
            return strpos($realPath, $realRoot) === 0;
        }
        
        return is_dir($path) && is_readable($path);
    }
    
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function isFileTypeAllowed($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = array_map('trim', explode(',', FM_ALLOWED_EXTENSIONS));
        return in_array($ext, $allowed) || in_array('*', $allowed);
    }
}

// ============================================================================
// AUTHENTICATION CLASS
// ============================================================================

class FileManagerAuth {
    
    public static function login($username, $password) {
        if ($username === FM_ADMIN_USER && password_verify($password, FM_ADMIN_PASS)) {
            $_SESSION['fm_logged_in'] = true;
            $_SESSION['fm_username'] = $username;
            $_SESSION['fm_login_time'] = time();
            return true;
        }
        return false;
    }
    
    public static function logout() {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    public static function isLoggedIn() {
        if (!isset($_SESSION['fm_logged_in']) || !$_SESSION['fm_logged_in']) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['fm_login_time'])) {
            $elapsed = time() - $_SESSION['fm_login_time'];
            if ($elapsed > FM_SESSION_TIMEOUT) {
                self::logout();
                return false;
            }
            $_SESSION['fm_login_time'] = time(); // Refresh
        }
        
        return true;
    }
}

// ============================================================================
// INITIALIZATION
// ============================================================================

// Security headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

// Session configuration
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
session_start();

// ============================================================================
// HANDLE ACTIONS
// ============================================================================

$message = '';
$messageType = '';

// Login handler
if (isset($_POST['fm_login'])) {
    if (FileManagerAuth::login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $message = 'Invalid credentials';
        $messageType = 'error';
    }
}

// Logout handler
if (isset($_GET['logout'])) {
    FileManagerAuth::logout();
}

// Check authentication
if (!FileManagerAuth::isLoggedIn()) {
    showLoginPage($message, $messageType);
    exit;
}

// Get current directory
$currentDir = isset($_GET['dir']) ? SecurityHelper::sanitizePath($_GET['dir']) : FM_ROOT_PATH;

// Handle direct path navigation
if (isset($_POST['goto_path']) && SecurityHelper::validateCSRFToken($_POST['csrf_token'] ?? '')) {
    $newPath = SecurityHelper::sanitizePath($_POST['path'] ?? '');
    if (is_dir($newPath) && is_readable($newPath)) {
        $currentDir = $newPath;
    } else {
        $message = 'Invalid path or permission denied';
        $messageType = 'error';
    }
}

// Validate current directory
if (!is_dir($currentDir) || !is_readable($currentDir)) {
    $currentDir = FM_ROOT_PATH;
}

$currentDir = realpath($currentDir);

if (!FM_ALLOW_SYSTEM_WIDE && !SecurityHelper::isPathSafe($currentDir, FM_ROOT_PATH)) {
    $currentDir = FM_ROOT_PATH;
    $message = 'Access denied to that directory';
    $messageType = 'error';
}

// Handle file operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!SecurityHelper::validateCSRFToken($_POST['csrf_token'])) {
        $message = 'Security validation failed';
        $messageType = 'error';
    } else {
        
        // Upload file
        if (isset($_POST['upload']) && isset($_FILES['file'])) {
            $target = $currentDir . '/' . basename($_FILES['file']['name']);
            $maxSize = FM_MAX_UPLOAD_SIZE_MB * 1024 * 1024;
            
            if ($_FILES['file']['size'] > $maxSize) {
                $message = 'File too large (max ' . FM_MAX_UPLOAD_SIZE_MB . 'MB)';
                $messageType = 'error';
            } elseif (!SecurityHelper::isFileTypeAllowed($_FILES['file']['name'])) {
                $message = 'File type not allowed';
                $messageType = 'error';
            } elseif (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                $message = 'File uploaded successfully';
                $messageType = 'success';
            } else {
                $message = 'Upload failed';
                $messageType = 'error';
            }
        }
        
        // Create folder
        if (isset($_POST['create_folder'])) {
            $folderName = basename($_POST['folder_name'] ?? '');
            if (!empty($folderName)) {
                $newFolder = $currentDir . '/' . $folderName;
                if (mkdir($newFolder, 0755)) {
                    $message = 'Folder created successfully';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to create folder';
                    $messageType = 'error';
                }
            }
        }
        
        // Create file
        if (isset($_POST['create_file'])) {
            $fileName = basename($_POST['file_name'] ?? '');
            if (!empty($fileName) && SecurityHelper::isFileTypeAllowed($fileName)) {
                $newFile = $currentDir . '/' . $fileName;
                if (file_put_contents($newFile, '') !== false) {
                    $message = 'File created successfully';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to create file';
                    $messageType = 'error';
                }
            } else {
                $message = 'Invalid file name or type not allowed';
                $messageType = 'error';
            }
        }
        
        // Rename
        if (isset($_POST['rename'])) {
            $oldName = SecurityHelper::sanitizePath($_POST['old_name'] ?? '');
            $newName = basename($_POST['new_name'] ?? '');
            if (!empty($oldName) && !empty($newName)) {
                $oldPath = $currentDir . '/' . $oldName;
                $newPath = $currentDir . '/' . $newName;
                if (rename($oldPath, $newPath)) {
                    $message = 'Renamed successfully';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to rename';
                    $messageType = 'error';
                }
            }
        }
        
        // Delete
        if (isset($_POST['delete'])) {
            $itemName = SecurityHelper::sanitizePath($_POST['item_name'] ?? '');
            $itemPath = $currentDir . '/' . $itemName;
            
            if (is_file($itemPath)) {
                if (unlink($itemPath)) {
                    $message = 'File deleted successfully';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to delete file';
                    $messageType = 'error';
                }
            } elseif (is_dir($itemPath)) {
                if (deleteDirectory($itemPath)) {
                    $message = 'Folder deleted successfully';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to delete folder';
                    $messageType = 'error';
                }
            }
        }
        
        // Save file content
        if (isset($_POST['save_file'])) {
            $filePath = SecurityHelper::sanitizePath($_POST['file_path'] ?? '');
            $content = $_POST['content'] ?? '';
            
            if (SecurityHelper::isFileTypeAllowed($filePath) && file_put_contents($filePath, $content) !== false) {
                $message = 'File saved successfully';
                $messageType = 'success';
            } else {
                $message = 'Failed to save file';
                $messageType = 'error';
            }
        }
    }
}

// Handle file download
if (isset($_GET['download'])) {
    $file = SecurityHelper::sanitizePath($_GET['download']);
    $filePath = $currentDir . '/' . $file;
    
    if (is_file($filePath) && is_readable($filePath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}

// Handle file editing
if (isset($_GET['edit'])) {
    $file = SecurityHelper::sanitizePath($_GET['edit']);
    $filePath = $currentDir . '/' . $file;
    
    if (is_file($filePath) && is_readable($filePath) && SecurityHelper::isFileTypeAllowed($filePath)) {
        showEditor($filePath, $currentDir);
        exit;
    }
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function formatSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    
    return rmdir($dir);
}

function getFileIcon($name) {
    if (is_dir($name)) {
        return '📁';
    }
    
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $icons = [
        'php' => '🐘',
        'html' => '🌐',
        'css' => '🎨',
        'js' => '📜',
        'json' => '📋',
        'xml' => '📋',
        'txt' => '📄',
        'md' => '📝',
        'log' => '📊',
        'sql' => '🗄️',
        'zip' => '📦',
        'jpg' => '🖼️',
        'jpeg' => '🖼️',
        'png' => '🖼️',
        'gif' => '🖼️',
    ];
    
    return $icons[$ext] ?? '📄';
}

function getUserDirectories() {
    $dirs = [];
    if (is_dir('/home') && is_readable('/home')) {
        $items = @scandir('/home');
        if ($items) {
            foreach ($items as $item) {
                if ($item != '.' && $item != '..') {
                    $path = '/home/' . $item;
                    if (is_dir($path) && is_readable($path)) {
                        $dirs[] = $path;
                    }
                }
            }
        }
    }
    return $dirs;
}

// ============================================================================
// VIEW FUNCTIONS
// ============================================================================

function showLoginPage($message, $messageType) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>File Manager - Login</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-box {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                width: 100%;
                max-width: 400px;
            }
            .login-box h1 {
                text-align: center;
                margin-bottom: 30px;
                color: #333;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                margin-bottom: 5px;
                color: #555;
                font-weight: 500;
            }
            .form-group input {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
            }
            .btn {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                font-weight: 500;
            }
            .btn:hover {
                opacity: 0.9;
            }
            .message {
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
                text-align: center;
            }
            .message.error {
                background: #fee;
                color: #c33;
                border: 1px solid #fcc;
            }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h1>🔐 File Manager</h1>
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="fm_login" class="btn">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}

function showEditor($filePath, $returnDir) {
    $content = file_get_contents($filePath);
    $fileName = basename($filePath);
    $fileSize = formatSize(filesize($filePath));
    $modTime = date('Y-m-d H:i:s', filemtime($filePath));
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit: <?php echo htmlspecialchars($fileName); ?></title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: #f5f5f5;
            }
            .editor-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .editor-info h2 {
                font-size: 18px;
                margin-bottom: 5px;
            }
            .editor-info p {
                font-size: 13px;
                opacity: 0.9;
            }
            .editor-actions {
                display: flex;
                gap: 10px;
            }
            .btn {
                padding: 8px 16px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
            }
            .btn-primary {
                background: #28a745;
                color: white;
            }
            .btn-secondary {
                background: white;
                color: #667eea;
            }
            .editor-container {
                padding: 20px;
                height: calc(100vh - 100px);
            }
            textarea {
                width: 100%;
                height: 100%;
                padding: 15px;
                font-family: 'Courier New', monospace;
                font-size: 14px;
                border: 1px solid #ddd;
                border-radius: 5px;
                resize: none;
                background: white;
            }
        </style>
    </head>
    <body>
        <div class="editor-header">
            <div class="editor-info">
                <h2>📝 <?php echo htmlspecialchars($fileName); ?></h2>
                <p><?php echo htmlspecialchars($filePath); ?> • <?php echo $fileSize; ?> • Modified: <?php echo $modTime; ?></p>
            </div>
            <div class="editor-actions">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::generateCSRFToken(); ?>">
                    <input type="hidden" name="file_path" value="<?php echo htmlspecialchars($filePath); ?>">
                    <input type="hidden" name="content" id="hidden-content">
                    <button type="submit" name="save_file" class="btn btn-primary" onclick="document.getElementById('hidden-content').value = document.getElementById('editor').value;">💾 Save</button>
                </form>
                <a href="?dir=<?php echo urlencode($returnDir); ?>" class="btn btn-secondary">← Back</a>
            </div>
        </div>
        <div class="editor-container">
            <textarea id="editor"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
    </body>
    </html>
    <?php
}

// ============================================================================
// MAIN FILE MANAGER VIEW
// ============================================================================

// Get directory contents
$items = scandir($currentDir);
$files = [];
$folders = [];

foreach ($items as $item) {
    if ($item == '.') continue;
    if ($item == '..' && $currentDir != '/') {
        $folders[] = '..';
        continue;
    }
    if ($item == '..' && $currentDir == '/') continue;
    if (!FM_SHOW_HIDDEN && $item[0] == '.') continue;
    
    $fullPath = $currentDir . '/' . $item;
    if (is_dir($fullPath)) {
        $folders[] = $item;
    } else {
        $files[] = $item;
    }
}

sort($folders);
sort($files);
$allItems = array_merge($folders, $files);

// Generate breadcrumb
$pathParts = explode('/', $currentDir);
$breadcrumb = [];
$buildPath = '';

foreach ($pathParts as $part) {
    if (empty($part)) {
        $breadcrumb[] = ['name' => '/', 'path' => '/'];
        $buildPath = '';
    } else {
        $buildPath .= '/' . $part;
        $breadcrumb[] = ['name' => $part, 'path' => $buildPath];
    }
}

// Get user directories
$userDirs = getUserDirectories();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f5f5;
            font-size: 14px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .header-top h1 {
            font-size: 22px;
        }
        .user-info {
            font-size: 13px;
            opacity: 0.9;
        }
        .current-path {
            background: rgba(40, 167, 69, 0.9);
            padding: 8px 12px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .breadcrumb {
            background: rgba(255,255,255,0.15);
            padding: 8px 12px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .breadcrumb a {
            color: white;
            text-decoration: none;
            margin: 0 3px;
            font-weight: 500;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .breadcrumb span {
            margin: 0 3px;
            opacity: 0.7;
        }
        .path-input-box {
            display: flex;
            gap: 10px;
        }
        .path-input-box input {
            flex: 1;
            padding: 8px 12px;
            border: 2px solid #667eea;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        .path-input-box button {
            padding: 8px 20px;
            background: white;
            color: #667eea;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        .quick-nav {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .quick-nav-btn {
            padding: 6px 12px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .quick-nav-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .quick-nav-btn.active {
            background: rgba(40, 167, 69, 0.8);
            border-color: rgba(40, 167, 69, 1);
        }
        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .toolbar {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary { background: #667eea; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .message {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .file-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .file-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .file-name span {
            font-size: 18px;
        }
        .file-name a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .file-name a:hover {
            text-decoration: underline;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .action-btn {
            padding: 4px 10px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .empty-state p {
            font-size: 16px;
            margin-top: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            backdrop-filter: blur(3px);
        }
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .modal-content h3 {
            margin-bottom: 20px;
