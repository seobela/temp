<?php
/**
 * ============================================================
 * PROFESSIONAL SINGLE-FILE PHP FILE MANAGER
 * ============================================================
 * Author: Clean-room implementation
 * Purpose: Secure website file management
 * ============================================================
 */

/* ===================== CONFIGURATION ===================== */

define('FM_ADMIN_USER', 'admin');
define('FM_ADMIN_PASS', password_hash('admin', PASSWORD_DEFAULT)); // CHANGE THIS
define('FM_SESSION_TIMEOUT', 3600);
define('FM_ROOT_PATH', __DIR__);
define('FM_SHOW_HIDDEN', false);
define('FM_MAX_UPLOAD_SIZE_MB', 50);
define('FM_ALLOW_SYSTEM_WIDE', false);

define('FM_ALLOWED_EXTENSIONS', [
    'txt','php','html','css','js','json','xml','htaccess','md','log',
    'sql','csv','ini','conf','yml','yaml','hpp','cpp','c','h'
]);

/* ===================== SECURITY HEADERS ===================== */

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

/* ===================== SESSION ===================== */

ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
session_start();

/* ===================== HELPERS ===================== */

function formatSize($bytes) {
    $units = ['B','KB','MB','GB','TB'];
    for ($i = 0; $bytes >= 1024 && $i < 4; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

function csrfToken() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function validateCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            die('Invalid CSRF token');
        }
    }
}

/* ===================== AUTH CLASS ===================== */

class FileManagerAuth {

    public static function check() {
        if (!isset($_SESSION['auth'])) {
            return false;
        }
        if (time() - $_SESSION['last'] > FM_SESSION_TIMEOUT) {
            session_destroy();
            return false;
        }
        $_SESSION['last'] = time();
        return true;
    }

    public static function login($u, $p) {
        if ($u === FM_ADMIN_USER && password_verify($p, FM_ADMIN_PASS)) {
            $_SESSION['auth'] = true;
            $_SESSION['user'] = $u;
            $_SESSION['last'] = time();
            return true;
        }
        return false;
    }

    public static function logout() {
        session_destroy();
        header("Location: ?");
        exit;
    }
}

/* ===================== PATH HANDLING ===================== */

$base = realpath(FM_ROOT_PATH);

$path = $_GET['path'] ?? $base;
$path = realpath($path);

if (!$path || strpos($path, $base) !== 0 && !FM_ALLOW_SYSTEM_WIDE) {
    $path = $base;
}

/* ===================== AUTH FLOW ===================== */

if (isset($_GET['logout'])) {
    FileManagerAuth::logout();
}

if (!FileManagerAuth::check()) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (FileManagerAuth::login($_POST['user'], $_POST['pass'])) {
            header("Location: ?");
            exit;
        }
        $error = "Invalid credentials";
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <style>
            body { font-family: Arial; background: linear-gradient(135deg,#667eea,#764ba2); display:flex; justify-content:center; align-items:center; height:100vh; }
            .box { background:#fff; padding:30px; border-radius:8px; width:300px; }
            input,button { width:100%; padding:10px; margin-top:10px; }
            button { background:#667eea; border:0; color:#fff; }
        </style>
    </head>
    <body>
    <form method="post" class="box">
        <h3>File Manager Login</h3>
        <?= isset($error) ? "<p style='color:red'>$error</p>" : "" ?>
        <input name="user" placeholder="Username" required>
        <input type="password" name="pass" placeholder="Password" required>
        <button>Login</button>
    </form>
    </body>
    </html>
    <?php
    exit;
}

/* ===================== ACTIONS ===================== */

validateCsrf();

/* File Upload */
if (!empty($_FILES['upload']['name'])) {
    $ext = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, FM_ALLOWED_EXTENSIONS)) {
        move_uploaded_file($_FILES['upload']['tmp_name'], $path . '/' . basename($_FILES['upload']['name']));
    }
}

/* Create Folder */
if (!empty($_POST['newfolder'])) {
    @mkdir($path . '/' . basename($_POST['newfolder']));
}

/* Create File */
if (!empty($_POST['newfile'])) {
    file_put_contents($path . '/' . basename($_POST['newfile']), '');
}

/* Delete */
if (!empty($_POST['delete'])) {
    $target = realpath($path . '/' . $_POST['delete']);
    if (is_dir($target)) {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? rmdir($f) : unlink($f);
        }
        rmdir($target);
    } elseif (is_file($target)) {
        unlink($target);
    }
}

/* ===================== DIRECTORY LIST ===================== */

$items = scandir($path);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>PHP File Manager</title>
<style>
body { margin:0; font-family:Arial; background:#f5f6fa; }
header { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; padding:15px; }
table { width:100%; border-collapse:collapse; background:#fff; }
th,td { padding:10px; border-bottom:1px solid #eee; }
tr:hover { background:#f1f1f1; }
.path { background:#e6fffa; padding:8px; font-family:monospace; margin:10px; }
.actions form { display:inline; }
button { padding:5px 10px; }
</style>
</head>
<body>

<header>
    Logged in as <b><?= htmlspecialchars($_SESSION['user']) ?></b>
    | <a href="?logout=1" style="color:#fff">Logout</a>
</header>

<div class="path"><?= htmlspecialchars($path) ?></div>

<table>
<tr>
    <th>Name</th><th>Size</th><th>Modified</th><th>Actions</th>
</tr>

<?php if ($path !== $base): ?>
<tr>
    <td colspan="4"><a href="?path=<?= urlencode(dirname($path)) ?>">⬅ Parent Directory</a></td>
</tr>
<?php endif; ?>

<?php foreach ($items as $item):
    if ($item === '.' || $item === '..') continue;
    if (!$FM_SHOW_HIDDEN && $item[0] === '.') continue;

    $full = $path . '/' . $item;
?>
<tr>
<td>
<?= is_dir($full) ? '📁' : '📄' ?>
<a href="<?= is_dir($full) ? '?path='.urlencode($full) : '?' ?>">
<?= htmlspecialchars($item) ?>
</a>
</td>
<td><?= is_file($full) ? formatSize(filesize($full)) : '-' ?></td>
<td><?= date('Y-m-d H:i', filemtime($full)) ?></td>
<td class="actions">
<form method="post" style="display:inline">
<input type="hidden" name="csrf" value="<?= csrfToken() ?>">
<input type="hidden" name="delete" value="<?= htmlspecialchars($item) ?>">
<button onclick="return confirm('Delete?')">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>

<br>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf" value="<?= csrfToken() ?>">
<input type="file" name="upload">
<button>Upload</button>
</form>

<form method="post">
<input type="hidden" name="csrf" value="<?= csrfToken() ?>">
<input name="newfolder" placeholder="New folder">
<button>Create Folder</button>
</form>

<form method="post">
<input type="hidden" name="csrf" value="<?= csrfToken() ?>">
<input name="newfile" placeholder="New file">
<button>Create File</button>
</form>

</body>
</html>
