<?php
include("database.php");
if(isset($_POST["aparato"]))
{
    $id = $_POST["aparato"];
    $sql = "SELECT * FROM ordenadores WHERE id = '$id'";
    $do = mysqli_query($link, $sql);
    $info = mysqli_fetch_assoc($do);
    echo date('d-m-Y H:i:s', $info["status_date"]);
}
?>