


<?php
if (isset($_GET["aula"])) {
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
    $aula = $_GET["aula"];
    $sql = "SELECT * FROM ordenadores WHERE ubicacion = '$aula'";
    if($do = mysqli_query($link, $sql)){}else{
        echo mysqli_error($link);
        exit;
    }
    require('equipos_etiquetas.php');
    $pdf = new PDF_Label('L7163');
    $pdf->AddPage();
    while ($row = mysqli_fetch_assoc($do)) {
        $coincidencia = false;
        $sql = "SELECT * FROM token WHERE aparato = ".$row["id"];
        $aparato = $row["id"];
        $do2 = mysqli_query($link, $sql);
        if($do2->num_rows > 0){
            $coincidencia = true;
            $code = mysqli_fetch_assoc($do2);
            $codigo = $code["token"];
        }
        while($coincidencia == false){
            $codigo = generateRandomString(6);
            $sql = "SELECT * FROM token WHERE token = '$codigo'";
            $do2 = mysqli_query($link, $sql);
            if($do2->num_rows == 0){
                $coincidencia = true;
                $sql = "INSERT INTO `token` (`id`, `aparato`, `token`, `usos`) VALUES (NULL, '$aparato', '$codigo', 0);";
                if(mysqli_query($link, $sql)){

                }else{
                    echo mysqli_error($link);
                }
            }
        }
        $date = date("d-m-Y", time());
        $text = sprintf("%s\n%s\n%s\n%s %s, %s", "I+D", 'IES FRANCISCO ASOREY', 'Codigo: '.$codigo, $row["nombre"], '', $date);
        $pdf->Add_Label($text, $codigo);
    }

    $pdf->Output();
}
