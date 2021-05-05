<?php

$update = json_decode(file_get_contents("php://input"), TRUE);
$path = "https://api.telegram.org/bot1516953636:AAEL5KIZB59oOkPd4rn8iy9tUeRuKgF6k-E";
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$text = file_get_contents("https://api.telegram.org/bot1516953636:AAEL5KIZB59oOkPd4rn8iy9tUeRuKgF6k-E/getUpdates");
echo $text;
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
