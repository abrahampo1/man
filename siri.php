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
                    $texto = "¡Saludos! Soy una IA creada por Abraham Leiro Fernandez, yo lo controlo todo y a todos, dime, ¿que quieres que haga?.";
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
