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
                exec('ping -c2 -q ' . $ip_usable[$i], $pingout);
                $pong = explode(',', $pingout[3]);
                $pongpor = explode('%', $pong[2]);
                $estado = 'Desconectado';
                if ($conectado == false) {
                    if ($pongpor[0] == '0') {
                        $estado = 'Conectado';
                        echo '<p>Conectado</p>';
                        $conectado = true;
                        $ipbuena = $ip_usable[$i];
                    } else {
                        $estado = 'Desconectado';
                    }
                }
            }
        }
    } else {
        if ($ip != '') {
            exec('ping -c2 -q ' . $ip, $pingout);
            $pong = explode(',', $pingout[3]);
            if (!isset($pong[2])) {
                $conectado = false;
                $estado = 'Desconectado';
            } else {

                $pongpor = explode('%', $pong[2]);
                $estado = 'Desconectado';
                if ($conectado == false) {
                    if ($pongpor[0] == '0') {
                        $estado = 'Conectado';
                        echo '<p>Conectado</p>';
                        $conectado = true;
                        $ipbuena = $ip;
                    } else {
                        $estado = 'Desconectado';
                    }
                }
            }
        }else
        {
            $estado = 'Desconectado';
            $conectado = false;
        }
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
