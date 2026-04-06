<?php
// VulnSphere - Main Router
// Entry point for the app

define('ROOT_PATH', __DIR__);
define('APP_VERSION', '1.0.4');

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/helpers.php';

// Start session
session_name('vs_session');
session_start();

// Auto-login via remember me cookie
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/app/models/User.php';
    require_once __DIR__ . '/app/models/Session.php';
    $uModel = new User();
    $user = $uModel->findByRememberToken($_COOKIE['remember_token']);
    if ($user) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['avatar']   = $user['avatar'];
        $_SESSION['token']    = $_COOKIE['remember_token'];
    }
}

$page   = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

// Route map
$routes = [
    'login'       => ['controller' => 'AuthController',    'method' => 'showLogin'],
    'do-login'    => ['controller' => 'AuthController',    'method' => 'login'],
    'register'    => ['controller' => 'AuthController',    'method' => 'showRegister'],
    'do-register' => ['controller' => 'AuthController',    'method' => 'register'],
    'logout'      => ['controller' => 'AuthController',    'method' => 'logout'],
    'feed'        => ['controller' => 'PostController',    'method' => 'feed'],
    'post-create' => ['controller' => 'PostController',    'method' => 'create'],
    'post-delete' => ['controller' => 'PostController',    'method' => 'delete'],
    'post-like'   => ['controller' => 'PostController',    'method' => 'like'],
    'comment-add' => ['controller' => 'CommentController', 'method' => 'add'],
    'comment-get' => ['controller' => 'CommentController', 'method' => 'getForPost'],
    'profile'     => ['controller' => 'UserController',    'method' => 'profile'],
    'edit-profile'=> ['controller' => 'UserController',    'method' => 'editProfile'],
    'search'      => ['controller' => 'UserController',    'method' => 'search'],
    'api-user'    => ['controller' => 'UserController',    'method' => 'apiUserInfo'],
    'admin'       => ['controller' => 'AdminController',   'method' => 'panel'],
    'admin-del-user'  => ['controller' => 'AdminController', 'method' => 'deleteUser'],
    'admin-del-post'  => ['controller' => 'AdminController', 'method' => 'deletePost'],
    'admin-query'     => ['controller' => 'AdminController', 'method' => 'runQuery'],
    'admin-role'      => ['controller' => 'AdminController', 'method' => 'updateRole'],
];

logDebug("Request", ['page' => $page, 'method' => $_SERVER['REQUEST_METHOD']]);

if (!isset($routes[$page])) {
    if (isLoggedIn()) {
        redirect('/index.php?page=feed');
    } else {
        redirect('/index.php?page=login');
    }
}

$route      = $routes[$page];
$ctrlName   = $route['controller'];
$methodName = $route['method'];

$ctrlFile = __DIR__ . '/app/controllers/' . $ctrlName . '.php';

if (!file_exists($ctrlFile)) {
    http_response_code(500);
    die("Controller not found: $ctrlName");
}

require_once $ctrlFile;

// Load sub-models as needed
$modelFiles = glob(__DIR__ . '/app/models/*.php');
foreach ($modelFiles as $mf) {
    require_once $mf;
}

$controller = new $ctrlName();

if (!method_exists($controller, $methodName)) {
    http_response_code(500);
    die("Method not found: $methodName");
}

$controller->$methodName();
