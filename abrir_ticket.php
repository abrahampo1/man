<style>
    h1 {
        font-family: 'Ubuntu', sans-serif;
        text-align: center;
    }

    h2 {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        text-align: center;
    }

    .card {
        justify-content: center;
        flex-direction: row;
    }

    input {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        border: 0px solid black;
        padding: 20px;
    }

    button {
        text-decoration: none;
        background-color: black;
        border: 0pc solid black;
        color: white;
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .articulo {
        width: auto;
    }
</style>

<?php
if (isset($_POST["profeclave"])) {
    $profeclave = $_POST["profeclave"];
    include("database.php");
    $sql = "SELECT * FROM ajustes WHERE nombre = 'profeclave'";
    $do = mysqli_query($link, $sql);
    $result = mysqli_fetch_assoc($do);
    if ($profeclave != $result["valor"]) {
        echo "Clave de profesor incorrecta. <br>Atte. Departamento de I+D Asorey";
        exit;
    }
} else {
    echo "Acceso sin autorización detectado. <br> Este equipo acaba de quedar registrado, si piensas que se trata de un error, vuelve a intentarlo. <br><br>Atte. Departamento de I+D Asorey";
    exit;
}
?>


<head>
    <title>Hola Instalador</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
</head>

<div class="card">
    <div style="text-align: center; ">
        <img src="img/logoi+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
        <div>
            <h1>Equipo: {insertar equipo}</h1>
            <div style="margin-top: 40px;">
                <form action="" method="post">
                    <div id="step1" style="display: none;">
                        <input type="text" id="name" name="name" placeholder="Escribe aqui su nombre"><br>
                        <button type="button" onclick="next()">Siguiente</button>
                    </div>
                    <div id="step2" style="display: none;">
                        <input type="text" id="incidencia_breve" name="incidencia_breve" placeholder="Indique el titulo de la incidencia (ej: no enciende)"><br>
                        <input type="textarea" id="incidencia" name="incidencia" placeholder="Explique brevemente cual es la incidencia"><br>
                        <button type="submit" onclick="next()">Siguiente</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



<script>
    var nombre;
    var equipo;
    var step = 1;
    window.onload = function() {
        document.getElementById("step" + step).style.display = "flex";
    }

    function next() {

        
        nombre = document.getElementById("name").value;
        if (nombre != "") {
            document.getElementById("step" + step).style.display = "none";
            step++;
            document.getElementById("step" + step).style.display = "flex";
            equipo = document.getElementById("equipo").value;
        }else{
        }


    }

    function cancel() {
        location.reload();
    }
</script>