<?
$errores=array(
    'El código de el aparato introducido no es válido 😅',
    'No se ha podido actualizar la base de datos.',
    'El codigo del ticket no es valido, pidele a un administrador que lo revise o intentalo de nuevo mas tarde.',
    'Las contraseñas no coinciden.',
    'Error al conectar con la base de datos',
    'No perteneces a forocoches premium :roto2:'
);
if(!isset($_GET['e']))
{
    header('Location: index.php');
}else
{
    $error = $_GET['e'];
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
<title>Error :(</title>
</head>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
<div class="container">
  <div class="vertical-center">
    <h1 style="font-family: Roboto;">Error :(</h1>
    <h4 style="font-family: Roboto;"><?echo $errores[$error]?></h4>
  </div>
</div>
