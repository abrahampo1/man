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
if ($info_aparato["ip_buena"] != '') {
    $ip = $info_aparato["ip_buena"];
}
if ($ip == '') {
    $ip = 'SIN ASIGNAR';
}

$horario = array('8:00', '8:50', '9:40', '10:30', '10:55', '11:45', '12:35', '13:25', '15:30', '16:20', '17:10', '18:00', '18:30', '19:20', '20:10', '21:00');
$dias = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');

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
                                        <img class="img-fluid px-1 px-sm-4 mt-2 mb-4" style="width: 10rem;" src="img/mantenimiento.svg" alt="">
                                    </div>
                                    <?php
                                    $sql = "SELECT * FROM aulas WHERE id = " . $info_aparato["ubicacion"];
                                    $do = mysqli_query($link, $sql);
                                    $aula = mysqli_fetch_assoc($do);
                                    ?>
                                    <p style="text-align: center;">El Profesor@ <?php echo $info_ticket['usuario'] ?>, ha descrito que <?php echo $info_ticket['descripcion'] ?><br><br>Información del equipo:<br>Ubicacion: <?php echo $aula["nombre"] ?><br>Identificador: <?php echo $info_aparato['nombre'] ?><br>IP: <?php echo $ip ?><br>CPU: <?php echo $info_aparato['cpu'] ?><br>RAM: <?php echo $info_aparato['ram'] ?><br>DISCO DURO: <?php echo $info_aparato['disco'] ?></p>
                                    <p>
                                    <h2>Horarios Recomendados (Tienes 50mins por cada hora):</h2><br>
                                    <?php
                                    $horas = explode(';', $aula["horario"]);
                                    $hora = 1;
                                    $dia_de_la_semana = date("N", time());
                                    for ($d = 0; $d < count($dias); $d++) {
                                        for ($i = 0; $i < count($horario); $i++) {
                                            if ($horas[$hora] == "0" && $d >= $dia_de_la_semana && $d < 5) {
                                                echo $dias[$d] . " " . $horario[$i] . "<hr>";
                                            }
                                            $hora++;
                                        }
                                    }
                                    ?>
                                    </p>
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