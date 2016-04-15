<?php
/*
 * ore.php
 * copyright 2009 jhon doe
 * This is joke framework. Don't use this when you are sane.
 */

function e($str)
{
    echo htmlspecialchars($str, ENT_QUOTES, "UTF-8");
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

/**
 * @param string|null $path URLのpath
 * @param string|null $func_name 実行される関数名
 * @return array|string|null path指定時は対応するrouteのfunc_name,未指定ならすべてのrouteを返す
 */
function route($path = null, $func_name = null)
{
    static $route_list = array();

    if (!is_null($path) && !is_null($func_name)) $route_list[$path] = $func_name;

    if (is_null($path)) return $route_list;

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


function path_regex()
{
    $regex_list = array();
    foreach (route() as $route => $cb) {
        $regex_list[$route] =
            '#\A' .
            preg_replace_callback(
                '#:([\w]+)#',
                function ($m) {
                    return "(?P<{$m[1]}>[^/]+)";
                },
                $route
            ) .
            '\z#'; // ex: #\A/post/(?P<id>[^/]+)\z#
    }
    return $regex_list;
}

function find_match_path()
{
    $uri = $_SERVER['REQUEST_URI'];
    foreach (path_regex() as $path => $regex) {
        if (preg_match($regex, $uri, $matches)) {
            $match_path = $path;
            break;
        }
    }
    if (!isset($match_path)) {
        return 'notfound';
    }

    foreach ($matches as $k => $v) {
        if (preg_match('/^[0-9]/u', $k)) {
            continue;
        }
        option($k, urldecode($v));
    }
    return $match_path;
}

function redirect($url)
{
    header("Location: {$url}");
    exit;
}

function run()
{
    $path = find_match_path();
    call_user_func(route($path));
}

function require_all($path)
{
    $file_list = glob($path . "*.php");
    foreach ($file_list as $file) {
        require_once($file);
    }
}

function notfound_default()
{
    http_response_code(404);
    if (file_exists(TEMPLATE_DIR . "notfound.php"))
        render('notfound.php');
    else
        echo "404 notfound";
}

route('notfound', 'notfound_default');

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
