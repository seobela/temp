<?php
/* ===================== CONFIGURATION ===================== */
define('FM_SESSION_TIMEOUT', 3600);
define('FM_ROOT_PATH', dirname(__FILE__));
define('FM_SHOW_HIDDEN', false);
define('FM_ALLOWED_EXTENSIONS', 'txt,php,html,css,js,json,xml,htaccess,md,log,sql,csv,ini,conf,yml,yaml,hpp,cpp,c,h');
define('FM_MAX_UPLOAD_SIZE_MB', 50);
define('FM_ALLOW_SYSTEM_WIDE', true);
/* ========================================================= */

ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
session_start();

/* ===================== SECURITY HEADERS ===================== */
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

/* ===================== HELPERS ===================== */
function formatSize($bytes) {
    $units = ['B','KB','MB','GB','TB'];
    for ($i=0; $bytes >= 1024 && $i < 4; $i++) $bytes /= 1024;
    return round($bytes, 2).' '.$units[$i];
}

function csrfToken() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function checkCSRF() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            die('Invalid CSRF token');
        }
    }
}

function cleanPath($path) {
    $path = str_replace(["\0"], '', $path);
    $real = realpath($path);
    if (!$real) return false;

    if (!FM_ALLOW_SYSTEM_WIDE) {
        if (strpos($real, realpath(FM_ROOT_PATH)) !== 0) {
            return false;
        }
    }
    return $real;
}

/* ===================== AUTH ===================== */
class FileManagerAuth {
    public static function login($u, $p) {
        if ($u === 'admin' && $p === 'bela') {
            $_SESSION['auth'] = true;
            $_SESSION['last'] = time();
            return true;
        }
        return false;
    }

    public static function check() {
        if (empty($_SESSION['auth'])) return false;
        if (time() - $_SESSION['last'] > FM_SESSION_TIMEOUT) {
            session_destroy();
            return false;
        }
        $_SESSION['last'] = time();
        return true;
    }

    public static function logout() {
        session_destroy();
    }
}

/* ===================== ACTIONS ===================== */
checkCSRF();

if (isset($_GET['logout'])) {
    FileManagerAuth::logout();
    header('Location: ?');
    exit;
}

if (isset($_POST['login'])) {
    if (!FileManagerAuth::login($_POST['user'], $_POST['pass'])) {
        $error = 'Invalid credentials';
    } else {
        header('Location: ?');
        exit;
    }
}

if (!FileManagerAuth::check()) {
?>
<!doctype html>
<html>
<head>
<title>File Manager Login</title>
<style>
body{background:#f4f6fb;font-family:Arial}
.box{width:320px;margin:120px auto;padding:25px;background:#fff;border-radius:8px;box-shadow:0 10px 30px rgba(0,0,0,.1)}
h2{text-align:center;margin-bottom:20px}
input,button{width:100%;padding:10px;margin:8px 0}
button{background:#667eea;color:#fff;border:0;border-radius:4px}
.error{color:#c00;text-align:center}
</style>
</head>
<body>
<div class="box">
<h2>File Manager</h2>
<?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
<form method="post">
<input type="hidden" name="csrf" value="<?=csrfToken()?>">
<input name="user" placeholder="Username" required>
<input name="pass" type="password" placeholder="Password" required>
<button name="login">Login</button>
</form>
</div>
</body>
</html>
<?php exit; }

/* ===================== FILE MANAGER ===================== */
$path = $_GET['path'] ?? FM_ROOT_PATH;
$path = cleanPath($path) ?: FM_ROOT_PATH;

$items = @scandir($path) ?: [];
$extAllowed = array_map('strtolower', explode(',', FM_ALLOWED_EXTENSIONS));
?>
<!doctype html>
<html>
<head>
<title>PHP File Manager</title>
<style>
body{margin:0;font-family:Arial;background:#f4f6fb}
header{background:linear-gradient(90deg,#667eea,#764ba2);color:#fff;padding:15px}
header span{float:right}
.container{padding:15px}
.path{background:#e6ffe6;padding:8px;font-family:monospace;border-radius:4px;margin-bottom:10px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:8px;border-bottom:1px solid #eee}
tr:hover{background:#f9f9f9}
a{color:#667eea;text-decoration:none}
.actions a{margin-right:8px}
.btn{padding:6px 10px;border-radius:4px;color:#fff}
.btn-blue{background:#667eea}
.btn-red{background:#dc3545}
.btn-green{background:#28a745}
footer{text-align:center;padding:10px;color:#777}
</style>
</head>
<body>

<header>
<strong>PHP File Manager</strong>
<span>User: admin | <a style="color:#fff" href="?logout=1">Logout</a></span>
</header>

<div class="container">

<div class="path"><?=htmlspecialchars($path)?></div>

<table>
<tr>
<th>Name</th>
<th>Size</th>
<th>Modified</th>
<th>Perms</th>
<th>Actions</th>
</tr>

<?php
if ($path !== dirname($path)) {
    echo "<tr><td>📁 <a href='?path=".urlencode(dirname($path))."'>..</a></td><td></td><td></td><td></td><td></td></tr>";
}

foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    if (!FM_SHOW_HIDDEN && $item[0] === '.') continue;

    $full = $path.DIRECTORY_SEPARATOR.$item;
    $isDir = is_dir($full);

    echo "<tr>";
    echo "<td>".($isDir ? "📁" : "📄")." ";
    if ($isDir) {
        echo "<a href='?path=".urlencode($full)."'>".htmlspecialchars($item)."</a>";
    } else {
        echo htmlspecialchars($item);
    }
    echo "</td>";

    echo "<td>".($isDir ? '-' : formatSize(filesize($full)))."</td>";
    echo "<td>".date('Y-m-d H:i', filemtime($full))."</td>";
    echo "<td>".substr(sprintf('%o', fileperms($full)), -4)."</td>";
    echo "<td class='actions'>";
    if (!$isDir) {
        echo "<a class='btn btn-green' href='?download=".urlencode($full)."'>Download</a>";
    }
    echo "</td>";
    echo "</tr>";
}
?>

</table>
</div>

<footer>Lightweight PHP File Manager</footer>

</body>
</html>
