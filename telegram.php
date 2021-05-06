<?php
include("database.php");
$sql = "SELECT * FROM ajustes WHERE nombre = 'grupotelegram'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
$grupo = $result["valor"];
$update = json_decode(file_get_contents("php://input"), TRUE);
$sql = "SELECT * FROM ajustes WHERE nombre = 'apitelegram'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
$api = $result["valor"];
$path = "https://api.telegram.org/bot" . $api;
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$hora = time();
$texto = "";
if (isset($_GET["texto"]) && $_GET["chatid"]) {
    session_start();
    include("database.php");
    if (!isset($_SESSION["user_id"])) {
        header("location: login.php");
    } else {
        $user_id = $_SESSION["user_id"];
        $sql = "SELECT * FROM tecnicos WHERE id = $user_id";
        if ($do = mysqli_query($link, $sql)) {
            $info = mysqli_fetch_assoc($do);
        } else {
            header("location: error.php?e=4");
        }
    }
    $texto = $_GET["texto"];
    $chatId = $_GET["chatid"];
    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
}

if (strtolower($message) == "hola") {
    $texto = "¡Hola! Soy un bot creado por Abraham Leiro Fernandez, dispuesto a hacer la gestión mucho mas sencilla.";
    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
}
if (strtolower($message) == "dame tus ids") {
    $texto = "¡Ojala pudiera!\n";
    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
}
if (strpos(strtolower($message), "apagar aula") !== false) {
    $texto = "Vaya, eso que quieres hacer es peligroso, voy a verificar que tienes acceso a estas funciones...";
    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    $sql = "SELECT * FROM tecnicos WHERE telegram = '$chatId'";
    $do = mysqli_query($link, $sql);
    if ($do->num_rows > 0) {
        $persona = mysqli_fetch_assoc($do);
        $aula = explode(" aula ", $message);
        $aula = $aula[1];
        $texto = "Vale " . $persona["nombre"] . ", veo que estas autorizado para hacer esto. Apagando el aula " . $aula;
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $ubicacion = "Aula " . $aula;
        $ordenadores = 0;
        $sql = "SELECT * FROM ordenadores WHERE ubicacion = '$ubicacion'";
        $do = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_assoc($do)) {
            $aparato = $row["id"];
            $sql = "UPDATE `ordenadores` SET `orden` = 'apagar' WHERE `ordenadores`.`id` = '$aparato';";
            if (mysqli_query($link, $sql)) {
                $ordenadores++;
            }
        }
        $texto = "He apagado ".$ordenadores." equipos. Se apagaran en 1 minuto. ⏳⏳⏳ (recuerda que puedes cancelar el apagado escribiendo 'shutdown -a' en el terminal de windows)";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    } else {
        $texto = "No tienes acceso a estas funciones.";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }
}

if (strtolower($message) == "abrir incidencia") {
    $texto = "¿Que equipo tiene el problema?";
    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if($do = mysqli_query($link, $sql)){
    $mensaje = mysqli_fetch_assoc($do);
    if($mensaje["respuesta"] == "¿Que equipo tiene el problema?"){
        $texto = "Asignando incidencia a ".$message.".";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $texto = "¿Que le pasa al equipo?";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }   
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if($do = mysqli_query($link, $sql)){
    $mensaje = mysqli_fetch_assoc($do);
    if($mensaje["respuesta"] == "¿Que le pasa al equipo?"){
        $texto = "Entonces al equipo ".$mensaje["mensaje"].". Le pasa que: '".$message."'.";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $texto = "¿Quieres abrir la incidencia?";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }   
}


$sql = "INSERT INTO `conversaciones_telegram` (`id`, `chatid`, `mensaje`, `respuesta`, `fecha`) VALUES (NULL, '$chatId', '$message', '$texto', '$hora');";
mysqli_query($link, $sql);