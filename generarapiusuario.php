<?php
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
    $random = generateRandomString(6);
    $aparato = $_POST["aparato"];
   
     
    $con = true;
    while($con == true)
    {
        $coincidencia = "SELECT * FROM tecnicos WHERE BINARY api = '$random'";
        $do = mysqli_query($link, $coincidencia);
        if($do->num_rows > 0)
        {
            $con = true;
            $random = generateRandomString(6);
        }else
        {
            $con = false;
        }
    } 
    $sql = "SELECT * FROM tecnicos WHERE id = '$aparato'";
    $do = mysqli_query($link, $sql);
    $ahora = time();
    if ($do->num_rows > 0) {
        $sql = "UPDATE `tecnicos` SET `api` = '$random' WHERE `tecnicos`.`id` = '$aparato';";
    } else {
        echo "Error completamente extraño y desconocido.";
    }
    if (mysqli_query($link, $sql)) {
        echo "Clave Mágica: ".$random;
    } else {
        echo mysqli_error($link);
        exit;
    }
}
