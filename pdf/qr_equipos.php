


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
        
        
        $date = date("d-m-Y", time());
        $text = sprintf("%s\n%s\n%s\n%s %s, %s", "I+D", 'IES FRANCISCO ASOREY', 'Codigo: '.$row["id"], $row["nombre"], '', $date);
        $pdf->Add_Label($text, $codigo);
    }

    $pdf->Output();
}
