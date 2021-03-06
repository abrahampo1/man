    <?php

    include('database.php');
    include('sinonimos.php');
    
    if (isset($_POST["api"])) {
        $api = $_POST["api"];
        $sql = "SELECT * FROM tecnicos WHERE api_siri = '$api'";
        $do = mysqli_query($link, $sql);
        if ($do->num_rows > 0) {
            $tech = mysqli_fetch_assoc($do);
            if (isset($_POST["orden"])) {
                $orden = $_POST["orden"];
                if (strtolower($orden) == "hola") {
                    echo "¡Saludos! Soy una IA creada por Abraham Leiro Fernández, yo lo controlo todo y a todos.";
                }
                if (in_array(strtolower($orden), $siguiente, true)) {
                    $json = file_get_contents("https://musica.asorey.net/api?next=Cx<(.JYD{L2{7D?@");
                    $json = json_decode($json, true);
                    echo "text;".$json["message"];
                }
                if (in_array(strtolower($orden), $hack, true)) {
                    $json = file_get_contents("https://musica.asorey.net/api?next=Cx<(.JYD{L2{7D?@&url=https://www.youtube.com/watch?v=dQw4w9WgXcQ");
                    $json = json_decode($json, true);
                    echo "text;".$json["message"];
                }
                if (in_array(strtolower($orden), $sonando, true)) {
                    $json = file_get_contents("https://musica.asorey.net/api?getplaydata=1");
                    $json = json_decode($json, true);
                    echo "text;Está sonando ".$json["title"];
                }
                foreach($volumen as $v) {
                    if (strpos(strtolower($orden),$v) !== false){
                        $volume = str_replace($v,"", strtolower($orden));
                        echo "text;";
                        $str_volume = (string)$volume;
                        $numero = $str_volume[strlen($str_volume)-1];
                        if($numero == "5"){
                            echo $payaso[$numero].". ";
                        }else if($str_volume == "13"){
                            echo $payaso["13"].". ";
                            
                        }
                        echo "¡De acuerdo! He ajustado el volumen al ".$volume."%";
                        $volume = $volume / 100;
                        $json = file_get_contents("https://musica.asorey.net/api?volume=Cx<(.JYD{L2{7D?@&value=$volume");
                        exit;
                    }
                }
                if (strpos(strtolower($orden), "apaga el ") !== false || strpos(strtolower($orden), "apaga la ") !== false) {
                    $aula = str_replace("apaga el ", "", strtolower($orden));
                    $aula = str_replace("apaga la ", "", strtolower($aula));
                    $orden_sin_apagar = str_replace("apaga ", "", strtolower($orden));
                    echo "text;De acuerdo, voy a intentar apagar " . $orden_sin_apagar . ". ";
                    $sql = "SELECT * FROM aulas WHERE nombre LIKE '%$aula%'";
                    $do = mysqli_query($link, $sql);
                    if ($do->num_rows == 0) {
                        echo "¡Vaya! Ese aula no se encuentra en mi sistema.";
                        exit;
                    }
                    $aula_info = mysqli_fetch_assoc($do);
                    $aula_id = $aula_info["id"];
                    $sql = "SELECT * FROM ordenadores WHERE ubicacion = '$aula_id'";
                    $do = mysqli_query($link, $sql);
                    $ordenadores = 0;
                    if ($do->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($do)) {
                            $aparato = $row["id"];
                            $sql = "UPDATE `ordenadores` SET `orden` = 'apagar' WHERE `ordenadores`.`id` = '$aparato';";
                            if (mysqli_query($link, $sql)) {
                                $ordenadores++;
                            }
                        }
                        echo "He apagado " . $ordenadores . " equipos. Se apagarán en 1 minuto.";
                    } else {
                        echo "¡Vaya! Ese aula está vacía.";
                        exit;
                    }
                }
                if (strpos(strtolower($orden), "muéstrame él ") !== false || strpos(strtolower($orden), "muéstrame la ") !== false) {
                    $aula = str_replace("muéstrame él ", "", strtolower($orden));
                    $aula = str_replace("muéstrame la ", "", strtolower($aula));
                    $sql = "SELECT * FROM aulas WHERE nombre LIKE '%$aula%' or identificador LIKE '%$aula%'";
                    $do = mysqli_query($link, $sql);
                    if ($do->num_rows == 0) {
                        echo "text;¡Vaya! Ese aula no se encuentra en mi sistema.";
                        exit;
                    }
                    $aula_info = mysqli_fetch_assoc($do);
                    $aula_id = $aula_info["id"];
                    $sql = "SELECT * FROM ordenadores WHERE ubicacion = '$aula_id'";
                    $do = mysqli_query($link, $sql);
                    $ordenadores = 0;
                    if ($do->num_rows > 0) {
                        echo "pdf;z";
                        while ($row = mysqli_fetch_assoc($do)) {
                            echo $row["ip"];
                            echo "<br>";
                            echo $row["nombre"];
                            echo "<hr>";
                        }
                    } else {
                        echo "text;¡Vaya! Ese aula está vacía.";
                        exit;
                    }
                }
            } else {
                echo "¡Hola! ¿Que quieres que haga?";
            }
        } else {
            echo "Buen intento, pero no tienes acceso y no te voy a decir por qué.";
        }
    } else {
        echo "Chaval, no te metas donde no debes.";
    }
