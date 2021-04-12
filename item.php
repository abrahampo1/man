<?php
session_start();
include('database.php');
include("googleimage.php");
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
} else {
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT * FROM tecnicos WHERE id = $user_id";
    if ($do = mysqli_query($link, $sql)) {
        $info = mysqli_fetch_assoc($do);
    } else {
        header("location: error.php?e=4");
    }
}
if(isset($_GET["i"])){
    $item = $_GET["i"];
    $sql = "SELECT * FROM inventario WHERE id = $item";
    $do = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($do);
}

if(isset($_GET["e"])){
    $sql = "DELETE FROM `inventario` WHERE `id` = $item";
    if(mysqli_query($link, $sql)){
        header("location: ./inventario");
    }
}

if (isset($_POST["equipo"])) {
    $equipo = $_POST["equipo"];
    $departamento = $_POST["departamento"];
    $categoria = $_POST["categoria"];
    $descripcion = $_POST["descripcion"];
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $estado = $_POST["estado"];
    $cantidad = $_POST["cantidad"];
    if (isset($_POST["serial"])) {
      $serial = $_POST["serial"];
    } else {
      $serial = "N/A";
    }
    $imagen = "";
    $sql = "UPDATE `inventario` SET `Equipo` = '$equipo', `Responsable` = '$user_id', `Departamento` = '$departamento', `Categoría` = '$categoria', `Descripción` = '$descripcion', `Marca` = '$marca', `Modelo` = '$modelo', `Serial` = '$serial', `Condición` = '$estado', `cantidad` = '$cantidad' WHERE `inventario`.`id` = $item;";
    if (!mysqli_query($link, $sql)) {
      echo 'Error en la base de datos. <br>' . mysqli_error($link);
    }else{
        Header('Location: item?i='.$item);
    }
  }
?>


<head>
    <title>Articulo</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<div class="d-flex justify-content-center">
    <a href="./inventario"><img style="text-align: center; margin: 20px; border-radius: 10px" class="center" width="auto" height="100px" src="./img/logo.png" alt=""></a>
</div>
<div class="container">
    <div class="row g-0">
     <div class="col-md-3">
     <?php
     if ($row["Marca"] != "" && $row["Modelo"] != "") {
        $image = $row["image"];
        echo '<img loading="lazy" ' . $image . ' style="margin-left: 10px; max-width: 280px; max-height:280px" width="auto" >';
      } else {
        echo '<img loading="lazy" data-lazysrc="img/inventario.png" style="margin: 10px;" height="180px" width="auto" >';
      }
     ?>
     </div>
     <div class="col-md-9">
     <form method="post" id="formadd" style="">
    <div class="form-group row">
      <label for="equipo" class="col-2 col-form-label">Equipo</label>
      <div class="col-10">
        <input id="equipo" name="equipo" type="text" class="form-control" value="<?php echo $row["Equipo"]; ?>" required="required">
      </div>
    </div>
    <div class="form-group row">
      <label class="col-2 col-form-label" for="departamento">Departamento</label>
      <div class="col-10">
        <select id="departamento" name="departamento" class="custom-select" required="required">
          <?php
          $sql = "SELECT * FROM departamentos";
          $do = mysqli_query($link, $sql);
          $departamento = $row["Departamento"];
          while ($row2 = mysqli_fetch_assoc($do)) {
            if($departamento == $row2["departamento"]){
                echo '<option value="' . $row2["id"] . '" selected>' . $row2["departamento"] . '</option>';
            }else{
                echo '<option value="' . $row2["id"] . '">' . $row2["departamento"] . '</option>';
            }
          }
          ?>
        </select>
      </div>

    </div>
    <div class="form-group row">
      <label for="categoria" class="col-2 col-form-label">Categoría</label>
      <div class="col-10">
        <input id="categoria" name="categoria" type="text" value="<?php echo $row["Categoría"]; ?>"   class="form-control" required="required">
      </div>
    </div>
    <div class="form-group row">
      <label for="descripcion" class="col-2 col-form-label">Descripción</label>
      <div class="col-10">
        <textarea id="descripcion" name="descripcion" cols="40" rows="5"  class="form-control" required="required"><?php echo $row["Descripción"]; ?> </textarea>
      </div>
    </div>
    <div class="form-group row">
      <label for="marca" class="col-2 col-form-label">Marca</label>
      <div class="col-10">
        <input id="marca" name="marca" type="text" class="form-control" value="<?php echo $row["Marca"]; ?>"  required="required">
      </div>
    </div>
    <div class="form-group row">
      <label for="modelo" class="col-2 col-form-label">Modelo</label>
      <div class="col-10">
        <input id="modelo" name="modelo" type="text" class="form-control" value="<?php echo $row["Modelo"]; ?>"  required="required">
      </div>
    </div>
    <div class="form-group row">
      <label for="serial" class="col-2 col-form-label">Serial</label>
      <div class="col-10">
        <input id="serial" name="serial" type="text" class="form-control" value="<?php echo $row["Serial"]; ?>" >
      </div>
    </div>
    <div class="form-group row">
      <label for="estado" class="col-2 col-form-label">Estado</label>
      <div class="col-10">
        <select id="estado" name="estado" class="custom-select" required="required" value="<?php echo $row["Estado"]; ?>" >
          <option value="nuevo">Nuevo</option>
          <option value="bien">Bien</option>
          <option value="mal">Mal</option>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="cantidad" class="col-2 col-form-label">Cantidad</label>
      <div class="col-10">
        <input id="cantidad" name="cantidad" type="number" class="form-control" required value="<?php echo $row["cantidad"]; ?>" >
      </div>
    </div>
    <div class="form-group row">
      <div class="offset-2 col-10">
        <button name="submit" type="submit" class="btn btn-primary">Guardar</button>
        <button class="btn btn-danger" type="button" onclick="document.getElementById('seguro').style.display = 'block'">Borrar articulo</button>
        <button class="btn btn-danger" id="seguro" style="display: none;" type="button" onclick="document.getElementById('segurodeverdad').style.display = 'block'">Estas seguro?</button>
        <button class="btn btn-danger" id="segurodeverdad" style="display: none;" type="button" onclick="document.getElementById('linkdeverdad').style.display = 'block'">pero estas seguro de verdad?</button>
        <a href="item?i=<?php echo $item ?>&e=1" id="linkdeverdad" style="display: none;" class="btn btn-danger">Pues clickea aqui</a>
      </div>
    </div>
  </form></div>
        
    </div>
</div>


<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

<script>
  function ReLoadImages() {
    $('img[data-lazysrc]').each(function() {
      //* set the img src from data-src
      $(this).attr('src', $(this).attr('data-lazysrc'));
    });
  }

  document.addEventListener('readystatechange', event => {
    if (event.target.readyState === "interactive") { //or at "complete" if you want it to execute in the most last state of window.
      ReLoadImages();
    }
  });
</script>

<script>
  if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
  }
</script>