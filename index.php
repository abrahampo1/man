<?
session_start();
include("database.php");
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
$horario = array('8:00', '8:50', '9:40', '10:30', '10:55', '11:45', '12:35', '13:25', '15:30', '16:20', '17:10', '18:00', '18:30', '19:20', '20:10', '21:00');
$dias = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
?>
<style>
    a {
        text-decoration: none;
    }

    a:link {
        text-decoration: none;
    }

    html {
        height: 100%;
    }

    body {
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
    }
</style>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CPM - Panel Principal</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>



<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" style="height: 100vh;">

                <?php include("topbar.php"); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Aparatos</h1>
                    </div>
                    <div class="row">
                        <?php
                        include('database.php');
                        $_SESSION["token"] = md5(uniqid(mt_rand(), true));
                        $token = $_SESSION["token"];
                        if (isset($_GET["b"])) {
                            $busc = $_GET['b'];
                            $sql = "SELECT * FROM ordenadores WHERE nombre LIKE '%$busc%' or id LIKE '$busc' or ip_buena LIKE '%$busc%' or ubicacion LIKE '%$busc%' or tipo LIKE '%$busc%' or cpu LIKE '%$busc%' or ram LIKE '%$busc%' or disco LIKE '%$busc%'";
                        } else if (isset($_GET["ub"]) && isset($_GET["au"])) {
                            $busc = $_GET['au'];
                            $sql = "SELECT * FROM ordenadores WHERE ubicacion = '$busc'";
                            $busqueda = mysqli_query($link, $sql);
                            while ($fila = mysqli_fetch_assoc($busqueda)) {

                                $date = time();
                                echo '<a style="text-decoration:none;" href="aparato?a=' . $fila['id'] . '&token=' . $token . '"><div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            ' . $fila['nombre'] . '</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-desktop" fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                        </div>';
                            }
                        } else {
                            $sql = 'SELECT * FROM aulas';
                            $busqueda = mysqli_query($link, $sql);
                            while ($fila = mysqli_fetch_assoc($busqueda)) {

                                $date = time();
                                echo '<a style="text-decoration:none;" href="?ub=' . $fila['nombre'] . '&au=' . $fila["id"] . '"><div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            ' . $fila['nombre'] . '</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chalkboard-teacher" fa-2x text-gray-300"></i>
                                    </div>
                                    
                                </div>
                            </div>
                            <a class="dropdown-item" data-toggle="modal" data-target="#aula1-settings" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                </a>
                        </div></a>
                        
                        </div>';
                            }
                        }

                        ?>
                    </div>

                </div>

            </div>
            <!-- End of Footer -->

        </div>

        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


</body>
<?php
$sql = "SELECT * FROM aulas";
$do = mysqli_query($link, $sql);
while($aula_info = mysqli_fetch_assoc($do)){
$hora = 1;
$horario_raw = "";
echo'<div class="modal fade" id="aula1-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ajustes Aula</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="h5 modal-body">
            <p>Horario</p>
            <select name="horario_matrix" id="dias" onchange="update()">';
                    for ($i = 0; $i < count($dias); $i++) {
                        echo '<option value="' . $dias[$i] . '">' . $dias[$i] . '</option>';
                    }
                echo'</select>';
                for ($d = 0; $d < count($dias); $d++) {
                    echo '<div style="display: none" id="' . $dias[$d] . '-section">';
                    for ($i = 0; $i < count($horario); $i++) {
                        echo $horario[$i] . ' <input onchange="update_check()" value="" id="' . $dias[$d] . '-' . $horario[$i] . '" type="checkbox"><br>';
                    }
                    echo '</div>';
                }

            echo'<form method="POST"><input type="hidden" id="horario" value=""><button class="btn btn-info">Guardar</button></form></div>


            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input type="hidden" name="cambioclave" id="">
            </div>
        </div>
    </div>
</div>';}
?>
</html>

<script>
    var horario = ['8:00', '8:50', '9:40', '10:30', '10:55', '11:45', '12:35', '13:25', '15:30', '16:20', '17:10', '18:00', '18:30', '19:20', '20:10', '21:00'];
    var semana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    var dias = document.getElementById("dias");
    var dia = dias.options[dias.selectedIndex].value;
    window.onload = function() {
        document.getElementById(dia + '-section').style.display = "block";
    }

    function update() {
        document.getElementById(dia + '-section').style.display = "none";
        dias = document.getElementById("dias");
        dia = dias.options[dias.selectedIndex].value;
        document.getElementById(dia + '-section').style.display = "block";
    }
    function update_check() {
        document.getElementById("horario").value = "";
        for(var i = 0; i < semana.length; i++){
            for(var d = 0; d < horario.length; d++){
                if(document.getElementById(semana[i] + "-" + horario[d]).checked){
                    document.getElementById("horario").value += ";1";
                }else{
                    document.getElementById("horario").value += ";0";
                }
                
            }
        }
        dias = document.getElementById("dias");
        dia = dias.options[dias.selectedIndex].value;
        document.getElementById(dia + '-section').style.display = "block";
    }
</script>