    <?php

    include('database.php');

    if(isset($_POST["api"])){
        $api = $_POST["api"];
        $sql = "SELECT * FROM tecnicos WHERE api = $api";
        $do = mysqli_query($link, $sql);
        if($do->num_rows > 0){
            $tech = mysqli_fetch_assoc($do);
            echo "Hola de nuevo, ".$tech["nombre"].".\n";
            if(isset($_POST["orden"])){
                $orden = $_POST["orden"];
                switch ($orden){
                    case "terminados":
                        $sql = "SELECT * FROM kits_token WHERE terminado = 1";
                        $do = mysqli_query($link, $sql);
                        echo "Se han terminado ".$do->num_rows." equipos.";
                        break;
                    case "empezados":
                        $sql = "SELECT * FROM kits_token WHERE terminado = 0  and equipo != ''";
                        $do = mysqli_query($link, $sql);
                        echo "".$do->num_rows." equipos están empezados y sin terminar.";
                        break;
                }
            }else{
                echo "La conexión con el departamento de I+Diotas ha sido establecida. ¿Que quieres que haga?";
            }
        }else{
            echo "Buen intento, pero no tienes acceso y no te voy a decir por qué.";
        }
    }else{
        echo "Chaval, no te metas donde no debes.";
    }