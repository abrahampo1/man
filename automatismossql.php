<?php

include("database.php");
for($i = 7; $i != 20; $i++){
    //$sql = "INSERT INTO `kits_token` (`id`, `kit`, `token`, `user`, `equipo`) VALUES (NULL, '2', 'SSD0$i', '', '');";
    mysqli_query($link, $sql);
    echo "$i Metido <br>";
}