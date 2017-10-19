<?php

for($i=0;$i<sizeof($_FILES['file']['name']);$i++)
{
    move_uploaded_file($_FILES['file']['tmp_name'][$i], "{$_POST['dir']}/{$_FILES['file']['name'][$i]}");

    echo "<br>{$_POST['dir']}/{$_FILES['file']['name'][$i]}<br>";
}
