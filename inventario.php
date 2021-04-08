<?php
include('database.php');
session_start();
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

if(isset($_POST["equipo"])){
    $equipo = $_POST["equipo"];
    $departamento = $_POST["departamento"];
    $categoria = $_POST["categoria"];
    $descripcion = $_POST["descripcion"];
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
}

?>

<head>
    <title>I+D+I+O+T+A+S Inventario</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<html>
<div>
    <h1 style="margin: 30px; text-align: center; font-family: 'Montserrat', sans-serif;">I+D+I+O+T+A+S<br>Inventario</h1>
</div>
<div class="container">
<form role="form" id="form-buscar">
<div class="form-group">
<div class="input-group">
<input id="1" class="form-control" type="text" name="search" placeholder="Buscar..." required/>
<span class="input-group-btn">
<button class="btn btn-success" type="submit">
<i class="glyphicon glyphicon-search" aria-hidden="true"></i> Buscar
</button>
</span>
</div>
</div>
</form>
<button class="btn btn-warning" style="margin: 20px;" onclick="document.getElementById('formadd').style.display = 'block'" >Añadir</button>
<form method="post" id="formadd" style="display: none;">
  <div class="form-group row">
    <label for="equipo" class="col-2 col-form-label">Equipo</label> 
    <div class="col-10">
      <input id="equipo" name="equipo" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label class="col-2 col-form-label" for="departamento">Departamento</label> 
    <div class="col-10">
      <input id="departamento" name="departamento" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="categoria" class="col-2 col-form-label">Categoría</label> 
    <div class="col-10">
      <input id="categoria" name="categoria" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="descripcion" class="col-2 col-form-label">Descripción</label> 
    <div class="col-10">
      <textarea id="descripcion" name="descripcion" cols="40" rows="5" class="form-control" required="required"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="marca" class="col-2 col-form-label">Marca</label> 
    <div class="col-10">
      <input id="marca" name="marca" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="modelo" class="col-2 col-form-label">Modelo</label> 
    <div class="col-10">
      <input id="modelo" name="modelo" type="text" class="form-control" required="required">
    </div>
  </div>
  <div class="form-group row">
    <label for="serial" class="col-2 col-form-label">Serial</label> 
    <div class="col-10">
      <input id="serial" name="serial" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="estado" class="col-2 col-form-label">Estado</label> 
    <div class="col-10">
      <select id="estado" name="estado" class="custom-select" required="required">
        <option value="nuevo">Nuevo</option>
        <option value="bien">Bien</option>
        <option value="mal">Mal</option>
      </select>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-2 col-10">
      <button name="submit" type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </div>
</form>
    <div class="row row-cols-2">
        <?php
        if(isset($_GET["search"])){
            $busqueda = $_GET["search"];
            $sql = "SELECT * FROM inventario WHERE Marca LIKE '%$busqueda%' or Modelo LIKE '%$busqueda%' or Equipo LIKE '%$busqueda%' or Departamento LIKE '%$busqueda%' LIMIT 15";
        }else{
            $sql = "SELECT * FROM inventario LIMIT 15";
        }
        if(!$do = mysqli_query($link, $sql)){
            echo mysqli_error($link);
            exit;
        }
        while($row = mysqli_fetch_assoc($do)){
            echo '<div class="col">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">';
                    if($row["imagen"]!= null){
                        echo '<img src="'.$row["imagen"].'" style="margin: 10px; max-width: 180px; height:180px" width="auto" >';
                    }else{
                        echo '<img src="img/inventario.png" style="margin: 10px;" height="180px" width="auto" >';
                    }
                    echo'
                        
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">'.$row["Marca"].' '.$row["Modelo"].'</h5>
                            <p class="card-text">Tenemos '.$row["cantidad"].'</p>
                            <p class="card-text">'.$row["Descripción"].'</p>
                            <p class="card-text"><small class="text-muted">ref: '.$row["Serial"].'</small></p>
                            <p class="card-text"><small class="text-muted">'.$row["Departamento"].'</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
        ?>
    </div>
</div>

</html>


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