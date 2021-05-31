    <?php

    include('database.php');

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
                if (strpos(strtolower($orden), "apaga el aula") !== false) {
                    $aula = str_replace("apaga el aula", "", strtolower($orden));
                    echo "De acuerdo, voy a intentar apagar el aula " . $aula . ". ";
                    $sql = "SELECT * FROM aulas WHERE nombre LIKE '%$aula%'";
                    $do = mysqli_query($link, $sql);
                    if ($do->num_rows == 0) {
                        echo "Vaya, no existe ese aula.";
                        exit;
                    }
                    $aula_info = mysqli_fetch_assoc($do);
                    $aula_id = $aula_info["id"];
                    $sql = "SELECT * FROM ordenadores WHERE ubicacion = '$aula_id'";
                    $do = mysqli_query($link, $sql);
                    if ($do->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($do)) {
                            $aparato = $row["id"];
                            $sql = "UPDATE `ordenadores` SET `orden` = 'apagar' WHERE `ordenadores`.`id` = '$aparato';";
                            if (mysqli_query($link, $sql)) {
                                $ordenadores++;
                            }
                        }
                        echo "He apagado " . $ordenadores . " equipos. Se apagaran en 1 minuto.";
                    } else {
                        echo "¡Vaya! Ese aula está vacía.";
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
