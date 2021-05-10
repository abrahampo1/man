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

    if(isset($_GET["e"])){
        $equipo = $_GET["e"];
        include("database.php");
        $sql = "SELECT * FROM ordenadores WHERE id = ".$equipo;
        $do = mysqli_query($link, $sql);
        $equipos = mysqli_fetch_assoc($do);
        $nombre_equipo = $equipos["nombre"];
    }else{
        header("location: http://iesfrancisoasorey.es");
        exit;
    }

?>


<head>
    <title>Departamento de incidencias - I+D+I Asorey</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
</head>

<div class="card" id="step1">
    <div style="text-align: center; ">
        <img src="img/logoi+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
        <div>
            <h1>Equipo: <?php echo $nombre_equipo ?></h1>
            <h2>Inserta la clave de profesor para continuar:</h2>
            <div style="margin-top: 40px;">
                <form action="https://mantenimiento.asorey.net/abrir_ticket" method="post">
                    <input type="password" id="name" name="profeclave" placeholder="Escribe aqui la clave"><br>
                    <input type="hidden" name="equipo">
                    <button onclick="next()">Siguiente</button>
                </form>
            </div>
        </div>
    </div>
</div>