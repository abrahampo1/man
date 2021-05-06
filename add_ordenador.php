<?php
session_start();
include("database.php");
if(!isset($_SESSION["user_id"]))
{
    header("location: login.php");
}
else
{
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT * FROM tecnicos WHERE id = $user_id";
    if($do = mysqli_query($link, $sql))
    { 
        $info = mysqli_fetch_assoc($do);
    }else
    {
        header("location: error.php?e=4");
    }
}

?>

<style>
    h1 {
        font-family: 'Ubuntu', sans-serif;
        text-align: center;
    }

    h2 {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        text-align: center;
    }

    .card {
        justify-content: center;
        flex-direction: row;
    }

    input {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        border: 0px solid black;
        padding: 20px;
    }

    button {
        text-decoration: none;
        background-color: black;
        border: 0pc solid black;
        color: white;
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .articulo {
        width: auto;
    }
</style>




<head>
    <title>Añadir Equipo - I+D Asorey</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
</head>

<div class="card" id="step1">
    <div style="text-align: center; ">
        <img src="img/logo i+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
        <div>
            <h1>Añadir Equipo</h1>
            <h2>Inserta el nombre:</h2>
            <div style="margin-top: 40px;">
                <form action="https://mantenimiento.asorey.net/abrir_ticket" method="post">
                    <input type="text" id="name" name="name" placeholder="Escribe el nombre del equipo"><br>
                    <button type="submit">Añadir</button>
                </form>
            </div>
        </div>
    </div>
</div>