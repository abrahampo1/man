    <?php

    include('database.php');

    if(isset($_POST["api"])){
        $api = $_POST["api"];
        $sql = "SELECT * FROM tecnicos WHERE api = $api";
        $do = mysqli_query($link, $sql);
        if($do->num_rows > 0){
            $tech = mysqli_fetch_assoc($do);
            echo "Hola de nuevo, ".$tech["nombre"].".";
            if(isset($_POST["orden"])){

            }else{
                echo "La conexión con el departamento de I+Diotas ha sido establecida. ¿Que quieres que haga?";
            }
        }else{
            echo "Buen intento, pero no tienes acceso y no te voy a decir por qué.";
        }
    }else{
        echo "Chaval, no te metas donde no debes.";
    }