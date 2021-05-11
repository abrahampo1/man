


<?php
if (isset($_GET["aula"])) {
    include("../database.php");
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
        $pdf->Add_Label($text, $row["id"]);
    }

    $pdf->Output();
}
