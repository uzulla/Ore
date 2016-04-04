<?php
define("APP_DIR", dirname(dirname(__FILE__))."/app");
require_once(dirname(dirname(__FILE__)) . "/app/ore.php");

option('HTML_TITLE', "なつかしのフレームワークです");

route('/', 'index');
route('/what/your/name/:name', 'what_your_name');
route('/redirect/sample', 'redirect');

run();

?>
