<?php

function what_your_name()
{
    $name = option('name')."様";
    redirect('/');

    render(
        'index.php',
        'layout.php',
        array(
            'html' => '<hr>',
            'name' => $name
        )
    );
}

?>