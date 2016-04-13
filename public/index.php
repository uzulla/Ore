<?php
require_once(dirname(dirname(__FILE__)) . "/app/ore.php");
define("DB_DSN", 'sqlite:' . dirname(dirname(__FILE__)) . "/db.sqlite");

option('HTML_TITLE', "なつかしの世界です");

route('/', 'post_list');
route('/post/create', 'post_create');
route('/post/:id', 'post_show');
route('/reset', 'post_reset');
run();
?>
