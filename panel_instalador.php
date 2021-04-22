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
        display: none;
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
        margin: 20px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .articulo {
        width: auto;
    }
</style>

<?php
include("database.php");
if (isset($_GET["qr"])) {
    $token = $_GET["qr"];
    $sql = "SELECT * FROM kits_token WHERE token = '$token'";
    $do = mysqli_query($link, $sql);
    if ($do->num_rows == 0) {
        header("location: 500");
    }
    $token = mysqli_fetch_assoc($do);
    $terminado = $token["terminado"];
    $id_token = $token["kit"];
    $id_raw = $token["id"];
    if ($token["user"] == "" && $token["equipo"] == "") {
        //redirigir al panel principal
        echo "He detectado que este codigo ya ha sido usado, pero el vago de abraham aun no ha programado la redireccion interna.";
        header("location: instalacion?t=" . $_GET["qr"]);
        exit;
    }
    $nombre = $token["user"];
    $equipo = $token["equipo"];
    $sql = "SELECT * FROM kits WHERE id = " . $id_token;
    $do = mysqli_query($link, $sql);
    $info_kit = mysqli_fetch_assoc($do);
    if (isset($_POST["terminado"])) {
        $sql = "UPDATE `kits_token` SET `terminado` = '1' WHERE `kits_token`.`id` = $id_raw;";
        mysqli_query($link, $sql);
        header("location panel_instalador?qr=" . $_GET["qr"]);
    }
}
?>

<head>
    <title><?php echo $nombre; ?> - Instalación</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
</head>
<html>

<div style="margin-top: 20px;">
    <?php
    if ($terminado == 1) {
        echo "<h1>Trabajo marcado como terminado.</h1>";
    }
    ?>
    <div class="card" id="topstep">
        <h1>Hola <?php echo $nombre; ?>,</h1>
        <h2>tienes que hacer lo siguiente:</h2>
    </div>
    <br>

    <?php
    $i = 0;
    $pasos = explode(";", $info_kit["pasos"]);
    for ($p = 0; $p < count($pasos)-1; $p++) {
        $paso = $pasos[$p];
        $sql = "SELECT * FROM kits_pasos WHERE kit = $paso ORDER BY paso asc";
        $do = mysqli_query($link, $sql);
        $steps = $do->num_rows;
        while ($kit_paso = mysqli_fetch_assoc($do)) {
            $i++;
            if($kit_paso["paso"] == 1){
                $sql = "SELECT * FROM pasos WHERE id = $paso";
                $result = mysqli_query($link, $sql);
                $datos_paso = mysqli_fetch_assoc($result);
                $nombre_paso = ": ".$datos_paso["nombre"];
            }else{
                $nombre_paso = "";
            }
            echo '<div class="card" id="step' . $i . '">
    <div style="text-align: center; ">
        <div>
            <h1>Paso ' . $i . $nombre_paso .'</h1>
            <h2>' . $kit_paso["descripcion"] . '</h2>
            <img src="' . $kit_paso["imagen"] . '" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
            <div style="margin-top: 40px;">
                ';
            if ($i > 1) {
                echo '<button onclick="back()">Atrás</button>';
            }
            if ($i != $steps || $p != count($pasos)) {
                echo '<button onclick="next()">Siguiente</button>';
            } else {
                echo '<form action="" method="post">
                    <input type="hidden" name="terminado" id="">
                    <button type="submit">Marcar terminado</button>
                </form>';
            }
            echo '
            </div>
        </div>
        
    </div>
</div>';
        }
    }
    ?>

    <div class="card" id="">
        <div style="text-align: center; ">
            <div>
                <h1>Material Asignado:</h1>
                <div style="margin-top: 40px;">
                    <div>
                        <?php
                        $sql = "SELECT * FROM kits_data WHERE kit = $id_token";
                        $do = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_assoc($do)) {
                            $sql = "SELECT * FROM inventario WHERE id = " . $row["articulo"];
                            $do2 = mysqli_query($link, $sql);
                            $articulo = mysqli_fetch_assoc($do2);
                            echo
                            '<div class="articulo">
                    <img ' . $articulo["imagen"] . ' alt="" width="200px" height="auto">
                    <h2>' . $articulo["Marca"] . ' ' . $articulo["Modelo"] . '</h2>
                    <h2>Cantidad: ' . $row["cantidad"] . '</h2>
                </div>';
                        }
                        ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</html>

<script>
    var step = 1;
    window.onload = function() {
        document.getElementById("step" + step).style.display = "flex";
        document.getElementById("topstep").style.display = "block";
    }

    function next() {
        if (step == 1) {
            document.getElementById("topstep").style.display = "none";
        }
        document.getElementById("step" + step).style.display = "none";
        step++;
        document.getElementById("step" + step).style.display = "flex";
    }

    function back() {
        if (step != 1) {
            document.getElementById("step" + step).style.display = "none";
            step--;
            document.getElementById("step" + step).style.display = "flex";
        }
    }
</script>