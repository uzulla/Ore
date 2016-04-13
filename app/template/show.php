<h1>けいじばん</h1>

<h2>投稿</h2>

<ul>
    <li>
        <?php e($row['name']); ?>:
        <?php e($row['text']); ?> /
        (<a href="/post/<?php e($row['id']); ?>"><?php e(date("Y-m-d H:i:s", $row['time'])); ?></a>)
    </li>
</ul>

<hr>

<h2>投稿フォーム</h2>
<form action="/post/create" method="post">
    名前:<input type="text" name="name"><br>
    内容:<input type="text" name="text"><br>
    <input type="submit">
</form>
