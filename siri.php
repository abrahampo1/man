    <?php

    include('database.php');

    if (isset($_POST["api"])) {
        $api = $_POST["api"];
        $sql = "SELECT * FROM tecnicos WHERE api_siri = $api";
        $do = mysqli_query($link, $sql);
        if ($do->num_rows > 0) {
            $tech = mysqli_fetch_assoc($do);
            if (isset($_POST["orden"])) {
                $orden = $_POST["orden"];
                switch ($orden) {
                    case "terminados":
                        $sql = "SELECT * FROM kits_token WHERE terminado = 1";
                        $do = mysqli_query($link, $sql);
                        echo "Se han terminado " . $do->num_rows . " equipos.";
                        break;
                    case "empezados":
                        $sql = "SELECT * FROM kits_token WHERE terminado = 0  and equipo != ''";
                        $do = mysqli_query($link, $sql);
                        echo "" . $do->num_rows . " equipos están empezados y sin terminar.";
                        break;
                    case "empezados_lista":
                        $sql = "SELECT * FROM kits_token WHERE terminado = 0  and equipo != ''";
                        $do = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_assoc($do)) {
                            echo "Codigo: " . $row["token"] . "<br>Kit: " . $row["kit"] . "<br>Equipo: " . $row["equipo"] . "<br>Tecnico: " . $row["user"] . "<hr>";
                        }
                        break;
                    case "muestrame el aula 5":
                        $sql = "SELECT * FROM ordenadores WHERE ubicacion = '1'";
                        $do = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_assoc($do)) {
                            $ip_usable = explode(';', $row['ip']);
                            $ip = '';
                            if (count($ip_usable) > 1) {
                                for ($i = 0; $i != count($ip_usable); $i++) {
                                    if ($ip_usable[$i] != "127.0.0.1" && $ip_usable[$i] != "" && strpos($ip_usable[$i], '169.254.') === false) {
                                        $ip = $ip_usable[$i];
                                    }
                                }
                            } else {
                                $ip = $row["ip"];
                            }
                            if ($row["ip_buena"] != '') {
                                $ip = $row["ip_buena"];
                            }
                            if ($ip == '') {
                                $ip = 'SIN ASIGNAR';
                            }
                            echo "Equipo: " . $row["nombre"] . "<br>IP: " . $ip . "<br><hr>";
                        }
                }
            } else {
                echo "La conexión con el departamento de I+Diotas ha sido establecida. ¿Que quieres que haga?";
            }
        } else {
            echo "Buen intento, pero no tienes acceso y no te voy a decir por qué.";
        }
    } else {
        echo "Chaval, no te metas donde no debes.";
    }
