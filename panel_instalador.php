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
include("database.php");
if(isset($_GET["qr"])){
    $token = $_GET["qr"];
    $sql = "SELECT * FROM kits_token WHERE token = '$token'";
    $do = mysqli_query($link, $sql);
    if($do->num_rows == 0){
        header("location: 500");
    }
    $token = mysqli_fetch_assoc($do);
    $terminado = $token["terminado"];
    $id_token = $token["kit"];
    $id_raw = $token["id"];
    if($token["user"] == "" && $token["equipo"] == ""){
        //redirigir al panel principal
        echo "He detectado que este codigo ya ha sido usado, pero el vago de abraham aun no ha programado la redireccion interna.";
        header("location: instalacion?t=".$_GET["qr"]);
        exit;
    }
    $nombre = $token["user"];
    $equipo = $token["equipo"];
    $sql = "SELECT * FROM kits WHERE id = ".$id_token;
    $do = mysqli_query($link, $sql);
    $info_kit = mysqli_fetch_assoc($do);
    if(isset($_POST["terminado"])){
        $sql = "UPDATE `kits_token` SET `terminado` = '1' WHERE `kits_token`.`id` = $id_raw;";
        mysqli_query($link, $sql);
        header("location panel_instalador?qr=".$_GET["qr"]);
    }
}
?>

<html>

<div class="card" id="step1">
    <div style="text-align: center; ">
        <img src="img/logo i+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
    </div>
</div>
<div style="margin-top: 20px;">
<?php
if($terminado == 1){
    echo "<h1>Trabajo marcado como terminado.</h1>";
}
?>
<h1>Hola <?php echo $nombre; ?>,</h1>
<h2>tienes que hacer lo siguiente:</h2>
<br>
<h2><?php echo $info_kit["instrucciones"]; ?></h2>
<br>
<div class="card" id="step3">
    <div style="text-align: center; ">
        <div>
            <h1>Material Asignado:</h1>
            <div style="margin-top: 40px;">
                <div>
                <?php
                $sql = "SELECT * FROM kits_data WHERE kit = $id_token";
                $do = mysqli_query($link, $sql);
                while($row = mysqli_fetch_assoc($do)){
                    $sql = "SELECT * FROM inventario WHERE id = ".$row["articulo"];
                    $do2 = mysqli_query($link, $sql);
                    $articulo = mysqli_fetch_assoc($do2);
                    echo 
                '<div class="articulo">
                    <img '.$articulo["imagen"].' alt="" width="200px" height="auto">
                    <h2>'.$articulo["Marca"].' '.$articulo["Modelo"].'</h2>
                    <h2>Cantidad: '.$row["cantidad"].'</h2>
                </div>';
                }
                ?>
                    
                </div>
                <form action="" method="post">
                    <input type="hidden" name="terminado" id="">
                    <button type="submit">Marcar terminado</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</html> 