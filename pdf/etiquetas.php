<?php
if (isset($_GET["kit"]) && $_GET["cant"]) {
    include("../database.php");
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    $kit = $_GET["kit"];
    $cant = $_GET["cant"];
    require('labels.php');
    $pdf = new PDF_Label('L7163');
    $pdf->AddPage();
    for ($i = 1; $i <= $cant; $i++) {
        $coincidencia = false;
        while($coincidencia == false){
            $codigo = generateRandomString(6);
            $sql = "SELECT * FROM kits_token WHERE token = '$codigo'";
            $do = mysqli_query($link, $sql);
            if($do->num_rows == 0){
                $coincidencia = true;
            }
        }
        $sql = "INSERT INTO `kits_token` (`id`, `kit`, `token`, `user`, `equipo`) VALUES (NULL, '$kit', '$codigo', '', '');";
        mysqli_query($link, $sql);
        $sql = "SELECT * FROM kits WHERE id = '$kit'";
        $do = mysqli_query($link, $sql);
        $result = mysqli_fetch_assoc($do);
        $date = date("d-m-Y", time());
        $text = sprintf("%s\n%s\n%s\n%s %s, %s", "I+D+I+o+t+a+s", 'IES FRANCISCO ASOREY', 'Codigo: '.$codigo, $result["nombre"], '', $date);
        $pdf->Add_Label($text);
    }

    $pdf->Output();
}
