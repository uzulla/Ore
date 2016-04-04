<?php

function notfound()
{
    http_response_code(404);
    render('notfound.php');
}

?>
