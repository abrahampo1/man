<?php
include('database.php');
if (isset($_POST["token"])) {
    $token = $_POST["token"];
    $sql = "SELECT * FROM token WHERE BINARY token = '$token'";
    if ($do = mysqli_query($link, $sql)) {
        if ($do->num_rows > 0) {
            $info_token = mysqli_fetch_assoc($do);
            $sql = "SELECT * FROM ordenadores WHERE id = ".$info_token["aparato"];
            $do = mysqli_query($link, $sql);
            $info_ordenador = mysqli_fetch_assoc($do);
            $consola = $info_ordenador["consola"];
            $usos = $info_token["usos"]+1;
            $id_token = $info_token["id"];
            $sql = "UPDATE `token` SET `usos` = '$usos' WHERE `token`.`id` = $id_token;";
            if(mysqli_query($link ,$sql)){}else{echo mysqli_error($link);}
            if(isset($_POST["consola"])){
                $consola = $_POST["consola"];
            }
            if(isset($_POST["tipo"])){
                if($_POST["tipo"]==0){
                $aparato = $info_token["aparato"];
                if(isset($_POST["cpu"]) && isset($_POST["red"]))
                {
                    $cpu = $_POST["cpu"];
                    $red = $_POST["red"];
                    $ram = $_POST["ramtotal"];
                    $disco = $_POST["discototal"];
                    $interfaces = explode(';', $red);
                    $ip_usable = "";
                    for($i = 0; $i < count($interfaces); $i++)
                    {
                        $datos_interfaces = explode('::', $interfaces[$i]);
                        if($datos_interfaces[0] != '')
                        {
                            $ip_usable .= $datos_interfaces[1].';';
                        }
                    }
                    
                    $ahora = time();
                    if($info_ordenador["orden"] == 'apagar')
                    {
                        echo ';apagar';
                    }else if($info_ordenador["orden"] != ""){
                        echo ';'.$info_ordenador["orden"];
                    }
                    
                    $sql = "UPDATE `ordenadores` SET `last_status` = 'Conectado', `orden` = '', `status_date` = '$ahora', `cpu` = '$cpu', `ram` = '$ram', `disco` = '$disco', `ip` = '$ip_usable', `consola` = '$consola' WHERE `ordenadores`.`id` = '$aparato';";
                    if(mysqli_query($link, $sql))
                    {
                        if(isset($consola)){
                            echo ';Datos actualizados. Info Recibida: '.$consola;
                        }else{
                            echo ';Datos actualizados';
                        }
                        
                        echo ';';
                    }
                }else
                {
                    echo 'Error en el API :( (cpu)';
                    exit;
                }
            }}else
            {
                echo 'Error en el API :(';
                exit;
            }
        } else {
            echo 'Token invalido    ';
            exit;
        }
    } else {
        echo 'Token invalido     ';
        exit;
    }
}
