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
$message = "";
$chatId = "";
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$hora = time();
$texto = "";

function generateRandomString($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if ($_POST["aparato"]) {
    include('database.php');
}




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
if (strtolower($message) == "tonto") {
    $texto = "Tonto tú 😜😜😜";
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
        $texto = "He apagado " . $ordenadores . " equipos. Se apagaran en 1 minuto. ⏳⏳⏳ (recuerda que puedes cancelar el apagado escribiendo 'shutdown -a' en el terminal de windows)";
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
if ($do = mysqli_query($link, $sql)) {
    $mensaje = mysqli_fetch_assoc($do);
    if ($mensaje["respuesta"] == "¿Que equipo tiene el problema?") {
        $texto = "Asignando incidencia a " . $message . ".";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $texto = "¿Que le pasa al equipo?";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if ($do = mysqli_query($link, $sql)) {
    $mensaje = mysqli_fetch_assoc($do);
    if ($mensaje["respuesta"] == "¿Que le pasa al equipo?") {
        $texto = "Entonces al equipo " . $mensaje["mensaje"] . ". Le pasa que: '" . $message . "'.";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $texto = "¿Quieres abrir la incidencia? (si o no)";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if ($do = mysqli_query($link, $sql)) {
    $mensaje = mysqli_fetch_assoc($do);
    if ($mensaje["respuesta"] == "¿Quieres abrir la incidencia? (si o no)") {
        if (strtolower($message) == "si") {
            $texto = "Asignando la incidencia, un momento...";
            file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
            $sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc LIMIT 2";
            $do = mysqli_query($link, $sql);
            while ($incidencia = mysqli_fetch_assoc($do)) {
                if ($incidencia["respuesta"] == "¿Que le pasa al equipo?") {
                    $equipo = $incidencia["mensaje"];
                }
                if ($incidencia["respuesta"] == "¿Quieres abrir la incidencia? (si o no)") {
                    $descripcion = $incidencia["mensaje"];
                }
            }
            $ahora = time();
            $sql = "SELECT * FROM ordenadores WHERE nombre = '$equipo'";
            $do = mysqli_query($link, $sql);
            if ($do->num_rows > 0) {
                $id_equipo = mysqli_fetch_assoc($do);
                $id_equipo = $id_equipo["id"];
            } else {
                $texto = "🚨 NO SE HA ENCONTRADO ESE EQUIPO EN LA BASE DE DATOS, VUELVE A INTENTARLO O REPORTA EL FALLO DIRECTAMENTE AL DEPARTAMENTO 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
            }
            $sql = "INSERT INTO `ticket` (`id`, `aparato`, `usuario`, `tipo_error`, `descripcion`, `tecnico`, `fecha`, `estado`) VALUES (NULL, '$id_equipo', '$chatId', 'Problema', '$descripcion', '1', '$ahora', 'pendiente');";
            if (mysqli_query($link, $sql)) {
                $texto = "🚨 Se ha reportado una incidencia para el equipo: $equipo. '$descripcion'. 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $grupo . "&text=" . $texto);
                $texto = "🚨 Incidencia reportada correctamente, algún tecnico se dirigirá al lugar... 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
            } else {
                $texto = "🚨 HA HABIDO UN ERROR AL REPORTAR LA INCIDENCIA, REPORTALO AL DEPARTAMENTO DIRECTAMENTE 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
            }
        } else {
            $texto = "De acuerdo, he cancelado.";
            file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        }
    }
}

if (strtolower($message) == "añadir equipo") {
    $texto = "¿Cual es su nombre?";
    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if ($do = mysqli_query($link, $sql)) {
    $mensaje = mysqli_fetch_assoc($do);
    if ($mensaje["respuesta"] == "¿Cual es su nombre?") {
        $texto = "Nombre: " . $message . ".";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $texto = "¿En que aula se encuentra?";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if ($do = mysqli_query($link, $sql)) {
    $mensaje = mysqli_fetch_assoc($do);
    if ($mensaje["respuesta"] == "¿En que aula se encuentra?") {
        $texto = "Nombre: " . $mensaje["mensaje"] . ". Aula: '" . $message . "'.";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
        $texto = "¿Quieres añadirlo? (si o no)";
        file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
    }
}
$sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc";
if ($do = mysqli_query($link, $sql)) {
    $mensaje = mysqli_fetch_assoc($do);
    if ($mensaje["respuesta"] == "¿Quieres añadirlo? (si o no)") {
        if (strtolower($message) == "si") {
            $texto = "Verificando acceso y añadiendo el equipo, un momento...";
            file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
            $sql = "SELECT * FROM conversaciones_telegram WHERE chatid = $chatId ORDER BY id desc LIMIT 2";
            $do = mysqli_query($link, $sql);
            while ($incidencia = mysqli_fetch_assoc($do)) {
                if ($incidencia["respuesta"] == "¿En que aula se encuentra?") {
                    $equipo = $incidencia["mensaje"];
                }
                if ($incidencia["respuesta"] == "¿Quieres añadirlo? (si o no)") {
                    $aula = $incidencia["mensaje"];
                }
            }
            $ahora = time();
            $sql = "SELECT * FROM ordenadores WHERE nombre = '$equipo'";
            $do = mysqli_query($link, $sql);
            if ($do->num_rows > 0) {
                $texto = "🚨 Ya hay un equipo con ese nombre. 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
                fin($chatId);
                exit;
            }
            $sql = "SELECT * FROM aulas WHERE nombre LIKE '%$aula%'";
            $do = mysqli_query($link, $sql);
            if ($do->num_rows > 0) {
                $aula_info = mysqli_fetch_assoc($do);
                $aula_id = $aula_info["id"];
            } else {
                $texto = "🚨 No existe ese aula en nuestro sistema, creala con /crearaula o revisa la interfaz web. 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
                fin($chatId);
                exit;
            }

            $sql = "INSERT INTO `ordenadores` (`id`, `nombre`, `ip`, `ubicacion`, `last_status`, `status_date`, `icono`, `tipo`, `cpu`, `ram`, `disco`, `ip_buena`, `orden`, `consola`) VALUES (NULL, '$equipo', '', '$aula_id', '', '0', 'fas fa-desktop', 'ordenador', '', '', '', '', '', '');";
            $sql2 = "SELECT * FROM tecnicos WHERE telegram = '$chatId'";
            $do2 = mysqli_query($link, $sql2);
            if ($do2->num_rows > 0) {
                $tecnico_data = mysqli_fetch_assoc($do2);
                $tecnico = $tecnico_data["id"];
                if (mysqli_query($link, $sql)) {
                    $id_equipo = mysqli_insert_id($link);
                    $random = generateRandomString(6);
                    $aparato = mysqli_insert_id($link);
                    $con = true;
                    while ($con == true) {
                        $coincidencia = "SELECT * FROM token WHERE BINARY token = '$random'";
                        $do = mysqli_query($link, $coincidencia);
                        if ($do->num_rows > 0) {
                            $con = true;
                            $random = generateRandomString(6);
                        } else {
                            $con = false;
                        }
                    }
                    $sql = "SELECT * FROM token WHERE aparato = '$aparato'";
                    $do = mysqli_query($link, $sql);
                    $ahora = time();
                    if ($do->num_rows > 0) {
                        $api = mysqli_fetch_assoc($do);
                        $api = $api["token"];
                    } else {
                        $sql = "INSERT INTO `token` (`id`, `token`, `aparato`, `usos`) VALUES (NULL, '$random', '$aparato', '0');";
                    }
                    if (mysqli_query($link, $sql)) {
                        $api = $random;
                        $unix_time = time();
                        
                        $sql = "SELECT * FROM aulas WHERE id = " . $aula_id;
                        $do = mysqli_query($link, $sql);
                        $aulainfo = mysqli_fetch_assoc($do);
                        $aula_nombre = $aulainfo["nombre"];
                        $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Creó el equipo <a href=aparato?a=$id_equipo> $equipo</a> en <a href=/?ub=$aula_nombre&au=$aula> $aula_nombre</a>', '$unix_time')";
                        mysqli_query($link, $sql);
                    } else {
                        echo mysqli_error($link);
                        exit;
                    }
                    $texto = "✅ Se ha añadido el equipo. Su API es: '$api' ✅ ";
                    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
                    fin($chatId);
                    
                } else {
                    $texto = "🚨 HA HABIDO UN ERROR AL AÑADIR EL EQUIPO, REPORTALO AL DEPARTAMENTO DIRECTAMENTE 🚨";
                    file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
                    fin($chatId);
                }
            } else {
                $texto = "🚨 No tienes acceso a estas funciones 🚨";
                file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
                fin($chatId);
            }
        } else {
            $texto = "De acuerdo, he cancelado.";
            file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=" . $texto);
            fin($chatId);
        }
    }
}
$sql = "INSERT INTO `conversaciones_telegram` (`id`, `chatid`, `mensaje`, `respuesta`, `fecha`) VALUES (NULL, '$chatId', '$message', '$texto', '$hora');";
mysqli_query($link, $sql);


function fin($chatId){
    $hora = time();
    include("database.php");
    $sql = "INSERT INTO `conversaciones_telegram` (`id`, `chatid`, `mensaje`, `respuesta`, `fecha`) VALUES (NULL, '$chatId', '', '-FIN CONVERSACIÓN-', '$hora');";
    mysqli_query($link, $sql);
}