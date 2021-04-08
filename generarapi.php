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
        $coincidencia = "SELECT * FROM token WHERE BINARY token = '$random'";
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
    $sql = "SELECT * FROM token WHERE aparato = '$aparato'";
    $do = mysqli_query($link, $sql);
    $ahora = time();
    if ($do->num_rows > 0) {
        $sql = "UPDATE `token` SET `token` = '$random' WHERE `token`.`aparato` = '$aparato';";
    } else {
        $sql = "INSERT INTO `token` (`id`, `token`, `aparato`, `usos`) VALUES (NULL, '$random', '$aparato', '0');";
    }
    if (mysqli_query($link, $sql)) {
        echo 'Token: '.$random;
    } else {
        echo mysqli_error($link);
        exit;
    }
}
