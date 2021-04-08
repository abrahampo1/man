<?
$errores=array(
    'Se ha creado el usuario de el técnico correctamente.',
    'Se ha cerrado el ticket correctamente',
    'Se ha reabierto el ticket correctmente'
);
if(!isset($_GET['o']))
{
    header('Location: index.php');
}else
{
    $error = $_GET['o'];
    if($error > array_count_values($errores))
    {
        header('Location: index.php');
    }
}
?>



<style>

.vertical-center {
  text-align: center;
  position: absolute;
  left: 50%;
  top: 45%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  -ms-transform: translateX(-50%);
  transform: translateX(-50%);
}

</style>
<head>
<title style="">OK :)</title>
</head>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
<div class="container">
  <div class="vertical-center">
    <h1 style="font-family: Roboto; color:green">OK :)</h1>
    <h4 style="font-family: Roboto;"><?echo $errores[$error]?></h4>
    <a href="index.php">INICIO</a>
  </div>
</div>
