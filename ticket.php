<?
session_start();
include('database.php');
if (!isset($_GET['t'])) {
    header('Location: error.php?e=2');
}
$ticket = $_GET['t'];
$sql = "SELECT * FROM ticket WHERE id=$ticket";
$do = mysqli_query($link, $sql);
$info_ticket = mysqli_fetch_assoc($do);
$idaparato = $info_ticket['aparato'];
$sql = "SELECT * FROM ordenadores WHERE id=$idaparato";
$do = mysqli_query($link, $sql);
$info_aparato = mysqli_fetch_assoc($do);
$tecnico = $info_ticket['tecnico'];
$sql = "SELECT * FROM tecnicos WHERE id=$tecnico";
$do = mysqli_query($link, $sql);
$info_tecnico = mysqli_fetch_assoc($do);
$sql = "SELECT * FROM ajustes WHERE nombre = 'grupotelegram'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
$grupo = $result["valor"];
$sql = "SELECT * FROM ajustes WHERE nombre = 'apitelegram'";
$do = mysqli_query($link, $sql);
$result = mysqli_fetch_assoc($do);
$api = $result["valor"];
$path = "https://api.telegram.org/bot" . $api;

if (isset($_POST['cerrar'])) {
    $sql = "UPDATE `ticket` SET `estado` = 'cerrado' WHERE `ticket`.`id` = $ticket";
    if ($do = mysqli_query($link, $sql)) {
        $unix_time = time();
        $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Cerro el <a href=ticket.php?t=$ticket>ticket $ticket</a>', '$unix_time')";
        if ($do = mysqli_query($link, $sql)) {
            header('location: ok.php?o=1');
        }
    }
    $texto = "✅  " . $info_tecnico["nombre"] . " ha cerrado la incidencia. En el equipo: " . $info_aparato["nombre"] . ", el fallo era: '" . $info_ticket["descripcion"] . "' 👌";
    file_get_contents($path . "/sendmessage?chat_id=" . $grupo . "&text=" . $texto);
}
if (isset($_POST['abrir'])) {
    $sql = "UPDATE `ticket` SET `estado` = 'pendiente' WHERE `ticket`.`id` = $ticket";
    if ($do = mysqli_query($link, $sql)) {
        $unix_time = time();
        $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Reabrió el <a href=ticket.php?t=$ticket>ticket $ticket</a>', '$unix_time')";
        if ($do = mysqli_query($link, $sql)) {
            header('location: ok.php?o=2');
        }
    }
}
$ip_usable = explode(';', $info_aparato['ip']);
$ip = '';
if (count($ip_usable) > 1) {
    for ($i = 0; $i != count($ip_usable); $i++) {
        if ($ip_usable[$i] != "127.0.0.1" && $ip_usable[$i] != "" && strpos($ip_usable[$i], '169.254.') === false) {
            $ip = $ip_usable[$i];
        }
    }
} else {
    $ip = $info_aparato["ip"];
}
if ($info["ip_buena"] != '') {
    $ip = $info_aparato["ip_buena"];
}
if ($ip == '') {
    $ip = 'SIN ASIGNAR';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ticket</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">

            <? include("topbar.php"); ?>
            <br>
            <div id="content">

                <div class="container-fluid">
                    <form action="" method="POST">

                        <?
                        if ($info_ticket['estado'] != 'cerrado') {
                            echo ('<button href="#" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-check"></i>
                            </span><input type="hidden" name="cerrar" value="1">
                            <span class="text">Marcar como arreglado</span>
                            </button>');
                        } else {
                            echo ('<button href="#" class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-tools"></i>
                            </span><input type="hidden" name="abrir" value="1">
                            <span class="text">Abrir incidencia de nuevo</span>
                            </button>');
                        }
                        ?>
                    </form>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-danger"><? echo $info_ticket['tipo_error'] ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-1 px-sm-4 mt-2 mb-4" style="width: 10rem;" <?
                                                                                                            if ($info_ticket["tipo_error"] = "288" && $info_ticket["descripcion"] == "ilitri paga el programa") {
                                                                                                                echo ('src="https://assets.change.org/photos/0/cx/am/hBCXaMRcxdBgKdy-800x450-noPad.jpg?1512577144"');
                                                                                                            } else {
                                                                                                                echo ('src="img/mantenimiento.svg"');
                                                                                                            }
                                                                                                            ?> alt="">
                                    </div>
                                    <p style="text-align: center;">El Profesor@ <? echo $info_ticket['usuario'] ?>, ha descrito que <? echo $info_ticket['descripcion'] ?><br><br>Información del equipo:<br>Ubicacion: <? echo $info_aparato['ubicacion'] ?><br>Identificador: <? echo $info_aparato['nombre'] ?><br>IP: <? echo $ip ?><br>CPU: <? echo $info_aparato['cpu'] ?><br>RAM: <? echo $info_aparato['ram'] ?><br>DISCO DURO: <? echo $info_aparato['disco'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
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