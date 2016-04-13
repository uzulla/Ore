<?php
/*
 * ore.php
 * copyright 2009 jhon doe
 * This is joke framework. Don't use this when you are sane.
 */

function e($str)
{
    echo htmlspecialchars($str);
}

function e_raw($str)
{
    echo $str;
}

function render($template, $layout = null, $params = array())
{
    $params = array_merge(option(), $params);
    // ここでオートエスケープするのが流行った気がする

    extract($params);

    ob_start();
    include(TEMPLATE_DIR . $template);
    $html = ob_get_clean();

    if ($layout != null) {
        ob_start();
        include(TEMPLATE_DIR . $layout);
        $html = ob_get_clean();
    }

    echo $html;
}

function route($path = null, $func_name = null)
{
    static $route_list = array();

    if (!is_null($path) && !is_null($func_name)) $route_list[$path] = $func_name;

    if (is_null($path == null)) return $route_list;

    if (isset($route_list[$path])) return $route_list[$path];

    return null;
}

function option($key = null, $val = null)
{
    // 関数終了時にも消えない配列として$var_listを宣言
    static $var_list = array();
    // $keyと$val両方になにかはいっていれば、$keyをキーに$valを保存
    if (!is_null($key) && !is_null($val)) $var_list[$key] = $val;
    // 引数指定無しなら、$var_listをまるごと返す
    if (is_null($key)) return $var_list;
    // $keyをキーとした値がvar_listにあれば、それを返す
    if (isset($var_list[$key])) return $var_list[$key];
    // みつからなかったので、nullを返す
    return null;
}


function route_regex()
{
    $regex_list = array();
    $route_list = route();

    foreach ($route_list as $route => $cb) {
        $regex_list[$route] = preg_replace_callback(
            '#:([\w]+)#',
            function ($m) {
                return "(?P<{$m[1]}>[^/]+)";
            },
            $route
        );
    }

    return $regex_list;
}

function find_route()
{
    $uri = $_SERVER['REQUEST_URI'];

    $matches = array();
    $match_route = false;
    $route_regex = route_regex();

    foreach ($route_regex as $route => $regex) {
        if (preg_match("#\A{$regex}\z#u", $uri, $matches)) {
            $match_route = $route;
            break;
        }
    }

    if ($match_route == false) return 'notfound';

    foreach ($matches as $k => $v) {
        if (preg_match('/^[0-9]/u', $k)) continue;
        $v = urldecode($v);
        option($k, $v);
    }

    return $match_route;
}

function redirect($url)
{
    header("Location: {$url}");
    exit;
}

function run()
{
    $route = find_route();
    call_user_func(route($route));
}

function require_all($path)
{
    $file_list = glob($path . "*.php");
    foreach ($file_list as $file) {
        require_once($file);
    }
}

route('notfound', 'notfound');

if (!defined("CONTROLLER_DIR")) {
    define("CONTROLLER_DIR", dirname(__FILE__) . "/controller/");
}
require_all(CONTROLLER_DIR);

if (!defined("DB_LIB_DIR")) {
    define("DB_LIB_DIR", dirname(__FILE__) . "/db/");
}
require_all(DB_LIB_DIR);

if (!defined('TEMPLATE_DIR')) {
    define("TEMPLATE_DIR", dirname(__FILE__) . "/template/");
}

?>
