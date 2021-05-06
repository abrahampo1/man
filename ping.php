<?php
include('database.php');
if (isset($_POST['sendping'])) {
    $ip = $_POST["sendping"];
    $aparato = $_POST["aparato"];
    $ip_usable = explode(';', $ip);
    $estado = 'Fallo 288';
    $conectado = false;
    if (count($ip_usable) > 1) {
        for ($i = 0; $i < count($ip_usable); $i++) {
            if ($ip_usable[$i] != '' && $ip_usable[$i] != '127.0.0.1') {
                
            }
        }
    }else
        {
            $estado = 'Desconectado';
            $conectado = false;
        }
    $ahora = time();
    if ($conectado == false) {
        echo '<p>Desconectado</p>';
    }
    if (isset($ipbuena)) {
        $sql = "UPDATE `ordenadores` SET `ip_buena` = '$ipbuena' WHERE `ordenadores`.`id` = $aparato";
        if (!mysqli_query($link, $sql)) {
            echo mysqli_error($link);
            echo '<p>Error en la base de datos</p>';
        } else {
        }
    }
    $sql = "UPDATE `ordenadores` SET `last_status` = '$estado', `status_date` = '$ahora' WHERE `ordenadores`.`id` = $aparato";
    if (!mysqli_query($link, $sql)) {
        echo mysqli_error($link);
        echo '<p>Error en la base de datos</p>';
    } else {
    }
} else {
    echo '<p>Error</p>';
}
