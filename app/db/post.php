<?php

function open_db()
{
    return new PDO(DB_DSN);
}

function db_post_load()
{
    $pdo = open_db();
    $stm = $pdo->prepare("SELECT * FROM post ORDER BY id");
    $stm->execute();
    $list = $stm->fetchAll();
    return $list;
}

function db_post_get($id)
{
    $pdo = open_db();
    $stm = $pdo->prepare("SELECT * FROM post WHERE id=:id");
    $stm->execute(array('id' => $id));
    $row = $stm->fetch();
    return $row;
}

function db_post_add($name, $text)
{
    $pdo = open_db();
    $stm = $pdo->prepare("INSERT INTO post ('name', 'text', 'time') VALUES (:name, :text, :time)");
    $stm->execute(array(
        'name' => $name,
        'text' => $text,
        'time' => time(),
    ));
}

function db_post_reset()
{
    $pdo = open_db();
    $pdo->query('DROP TABLE post;');
    $pdo->query(
        '
        CREATE TABLE post
        (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name text NOT NULL,
        text text NOT NULL,
        time text NOT NULL
        )
        ');
}

?>
