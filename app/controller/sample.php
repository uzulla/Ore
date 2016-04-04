<?php

function index()
{
    render(
        'index.php',
        'layout.php',
        array("name" => 'テスト', 'html' => '<hr>')
    );
}

?>
