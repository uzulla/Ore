<?php
function post_list()
{
    $list = model_post_load();

    render(
        'list.php',
        'layout.php',
        array("list" => $list)
    );
}

function post_show()
{
    $id = option('id');
    $row = model_post_get($id);

    if (!$row) {
        return notfound();
    }

    render(
        'show.php',
        'layout.php',
        array("row" => $row)
    );
}

function post_create()
{
    $name = $_POST['name'];
    $text = $_POST['text'];

    // バリデーションしてないの、ホントはダメです

    if (strlen($name) > 0 && strlen($name) > 0) {
        model_post_add($name, $text);
    }

    redirect('/');
}

function post_reset()
{
    model_post_reset();
    redirect('/');
}
