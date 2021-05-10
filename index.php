<?
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
                        $sql = 'SELECT * FROM ordenadores';
                        if (isset($_GET["b"])) {
                            $busc = $_GET['b'];
                            $sql = "SELECT * FROM ordenadores WHERE nombre LIKE '%$busc%' or id LIKE '$busc' or ip_buena LIKE '%$busc%' or ubicacion LIKE '%$busc%' or tipo LIKE '%$busc%' or cpu LIKE '%$busc%' or ram LIKE '%$busc%' or disco LIKE '%$busc%'";
                        }
                        $busqueda = mysqli_query($link, $sql);
                        while ($fila = mysqli_fetch_assoc($busqueda)) {
                            $date = time();
                            $date_status = $fila['status_date'];
                            $diff = $date - $date_status;
                            $diffmins = $diff / 60;
                            $diffminutos = $diffmins % 60;
                            $diffsecs = $diff % 60;
                            $diffhoras = floor($diffmins / 60);
                            $tiempo = '<div class="mb-0 text-gray-800">Hace ' . $diffhoras . ' horas ' . $diffminutos . ' mins y ' . $diffsecs . ' segundos</div>';
                            if(($date - $date_status) < 5)
                            {
                                $tiempo = '<div style="font-color:green; color:green" class="mb-0">CONEXION ESTABLECIDA</div>';
                            }
                            $ip = '';
                            $ip_usable = explode(';', $fila["ip"]);
                            if (count($ip_usable) > 0) {
                                for($i = 0; $i != count($ip_usable); $i++){
                                    if($ip_usable[$i] != "127.0.0.1" && $ip_usable[$i] != "" && strpos($ip_usable[$i], '169.254.') === false){
                                        $ip = $ip_usable[$i];
                                    }
                                }
                            } else {
                                $ip = $fila["ip"];
                            }
                            if ($fila["ip_buena"] != '') {
                                $ip = $fila["ip_buena"];
                            }
                            if ($ip == '') {
                                $ip = 'SIN ASIGNAR';
                            }
                            echo '<a style="text-decoration:none;" href="aparato?a=' . $fila['id'] . '"><div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            ' . $fila['nombre'] . '</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $fila['last_status'] . '</div>
                                        <div class="h9 mb-0 font-weight-bold text-gray-800">' . $ip . '</div>
                                        '.$tiempo.'
                                    </div>
                                    <div class="col-auto">
                                        <i class="' . $fila['icono'] . ' fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                        </div>';
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

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>