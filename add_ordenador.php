<?php
session_start();
include("database.php");

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
} else {
    $tecnico = $_SESSION["user_id"];
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT * FROM tecnicos WHERE id = $user_id";
    if ($do = mysqli_query($link, $sql)) {
        $info = mysqli_fetch_assoc($do);
    } else {
        header("location: error.php?e=4");
    }
}

if (isset($_POST["name"]) && isset($_POST["aula"])) {
    $nombre = $_POST["name"];
    $aula = $_POST["aula"];
    $sql = "INSERT INTO `ordenadores` (`id`, `nombre`, `ip`, `ubicacion`, `last_status`, `status_date`, `icono`, `tipo`, `cpu`, `ram`, `disco`, `ip_buena`, `orden`, `consola`) VALUES (NULL, '$nombre', '', '$aula', '', '0', 'fas fa-desktop', 'ordenador', '', '', '', '', '', '');";
    if ($do = mysqli_query($link, $sql)) {
        $unix_time = time();
        $id_equipo = mysqli_insert_id($link);
        $sql = "SELECT * FROM aulas WHERE id = " . $aula;
        $do = mysqli_query($link, $sql);
        $aulainfo = mysqli_fetch_assoc($do);
        $aula_nombre = $aulainfo["nombre"];
        $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Creó el equipo <a href=aparato.php?a=$id_equipo> $nombre</a> en <a href=/?ub=$aula_nombre&au=$aula> $aula_nombre</a>', '$unix_time')";
        mysqli_query($link, $sql);
        header("location: /");
    }
}

?>

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
        margin: 5px;
    }

    select {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        border: 0px solid black;
        padding: 20px;
        margin: 5px;
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




<head>
    <title>Añadir Equipo - I+D Asorey</title>
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
            <h1>Añadir Equipo</h1>
            <h2>Inserta el nombre:</h2>
            <div style="margin-top: 40px; display: block">
                <form action="" method="post">
                    <input type="text" id="name" name="name" placeholder="Nombre"><br><br>
                    <?php
                    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
                    $token = $_SESSION["token"];
                    echo '<input type="hidden" value="' . $token . '" name="csrf_token"><select name="aula" type="text" class="form-control form-control-user h5 mb-0 mr-3 font-weight-bold text-gray-800" >';
                    $sql = "SELECT * FROM aulas";
                    $do = mysqli_query($link, $sql);
                    while ($aula = mysqli_fetch_assoc($do)) {
                        echo '<option ';

                        echo '  value="' . $aula["id"] . '">' . $aula["nombre"] . '</option>';
                    }
                    echo '</select><br>';
                    ?><br><br>
                    <button type="submit">Añadir</button>
                </form>
            </div>
        </div>
    </div>
</div>