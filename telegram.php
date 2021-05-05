<?php

$update = json_decode(file_get_contents("php://input"), TRUE);
$path = "https://api.telegram.org/bot1516953636:AAEL5KIZB59oOkPd4rn8iy9tUeRuKgF6k-E";
$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

if(strtolower($message) == "hola"){
    $texto = "¡Hola! Soy un bot creado por Abraham Leiro Fernandez, dispuesto a hacer la gestión mucho mas sencilla.";
    file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=".$texto);
}