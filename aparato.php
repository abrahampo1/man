<?php
include('database.php');
session_start();
if (isset($_SESSION["user_id"])) {
} else {
    header('location: login.php');
}
if (!isset($_GET['a'])) {
    header('Location: error.php?e=0');
} else {
    $aparato = $_GET['a'];
}
function buscarbdo($sql)
{
    include('database.php');
    if (!$result = mysqli_query($link, $sql)) {
        echo mysqli_error($link);
        header('Location: error.php?e=1');
    }
    return $result;
}
function reload($aparato)
{
    header("location: aparato.php?a=$aparato");
}

$sql = "SELECT * FROM ordenadores WHERE id = $aparato";
$bdo = buscarbdo($sql);
$info = mysqli_fetch_assoc($bdo);
if (!isset($info['nombre'])) {
    header('Location: error.php?e=0');
}

if (isset($_GET['apagar'])) {
    $sql = "UPDATE `ordenadores` SET `orden` = 'apagar' WHERE `ordenadores`.`id` = '$aparato';";
    if (mysqli_query($link, $sql)) {
        header('location: index.php');
    }
}





$ip_antigua = $info["ip_buena"];
$nombre = $info["nombre"];
$ubicacion_antigua = $info["ubicacion"];
//Actualizar info, tengo que optimizar esta zona, no me llega el conocimiento
$tecnico = $_SESSION["user_id"];
if (isset($_POST["ip"])) {
    $ip_nueva = $_POST["ip"];
    $sql = "UPDATE `ordenadores` SET `ip_buena` = '$ip_nueva' WHERE `ordenadores`.`id` = $aparato";
    if (mysqli_query($link, $sql)) {
        $unix_time = time();
        $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Cambio la IP ($ip_antigua) a ($ip_nueva) en <a href=aparato.php?a=$aparato> $nombre</a>', '$unix_time')";
        mysqli_query($link, $sql);
        header("location: aparato.php?a=$aparato");
    } else {
        header("location: error.php?e=4");
    }
}
if (isset($_POST["ubicacion"])) {
    $var_nueva = $_POST["ubicacion"];
    $sql = "UPDATE `ordenadores` SET `ubicacion` = '$var_nueva' WHERE `ordenadores`.`id` = $aparato";
    if ($_POST["csrf_token"] == $_SESSION["token"]) {
        if (mysqli_query($link, $sql)) {
            $unix_time = time();
            $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Cambio la Ubicacion ($ubicacion_antigua) a ($var_nueva) en <a href=aparato.php?a=$aparato> $nombre</a>', '$unix_time')";
            mysqli_query($link, $sql);
            reload($aparato);
        } else {
            header("location: error.php?e=4");
        }
    }
}
if (isset($_POST["nombre"])) {
    $var_nueva = $_POST["nombre"];
    $sql = "UPDATE `ordenadores` SET `nombre` = '$var_nueva' WHERE `ordenadores`.`id` = $aparato";
    $unix_time = time();
    if ($_POST["csrf_token"] == $_SESSION["token"]) {
        if (mysqli_query($link, $sql)) {
            $sql = "INSERT INTO `actividad` (`id`, `persona`, `accion`, `fecha`) VALUES (NULL, '$tecnico', 'Cambio el nombre ($nombre) a ($var_nueva) en <a href=aparato.php?a=$aparato> $var_nueva</a>', '$unix_time')";
            mysqli_query($link, $sql);
            reload($aparato);
        } else {
            header("location: error.php?e=4");
        }
    }
}   

$_SESSION["token"] = md5(uniqid(mt_rand(), true));
$token = $_SESSION["token"];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Aparato</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <? include("topbar.php"); ?>
                <br>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <?
                        if (isset($_GET["edit"])) {
                            echo '<div class="row"><form method="post" action="aparato.php?a=' . $info["id"] . '"><input type="hidden" value="' . $token . '" name="csrf_token"><input name="nombre" type="text" class="form-control form-control-user h3 mb-0 text-gray-800" value="' . $info['nombre'] . '"><button class="btn btn-primary btn-user btn-block" type="submit">Guardar</button></form></div>';
                        } else {
                            echo '<h1 class="h3 mb-0 text-gray-800">' . $info['nombre'] . '</h1>';
                        }
                        ?>

                        <div>
                            <a href="aparato.php?a=<?php echo $info["id"] ?>&apagar=1" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i class="fas fa-paper fa-sm text-white-50"></i> Apagar</a>
                            <a href="#" data-toggle="modal" data-target="#api" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-paper fa-sm text-white-50"></i> API</a>
                            <a href="#" onclick="loaddata()" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-paper-plane fa-sm text-white-50"></i> Enviar Ping</a>
                            <?
                            if (isset($_GET["edit"])) {
                                echo '<a href="?a=' . $info["id"] . '" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                            class="fas fa-window-close fa-sm text-white-50"></i> Cancelar</a>';
                            } else {
                                echo '<a href="?a=' . $info["id"] . '&edit=1" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                            class="fas fa-pen-square fa-sm text-white-50"></i> Editar</a>';
                            }
                            ?>
                        </div>

                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Fecha de Actualizacion</div>
                                            <div id="tiempo" class="h5 mb-0 font-weight-bold text-gray-800">
                                                <? echo date('d-m-Y H:i:s', $info['status_date']) ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Estado del ping</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <div id="my_update_panel"></div>
                                                <p id="loading_spinner"></p>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <img width="40px" id="loading" class="text-center" src="img/loading-gif.gif">
                                            <i id="icon" class="fas fa-table-tennis fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">

                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">IP
                                                <?
                                                if ($info["ip"] == "288.288.288.288") {
                                                    echo ('<img weight="40px" height="40px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/Roto2.svg/1200px-Roto2.svg.png">');
                                                }
                                                ?>
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <?
                                                    $ip_usable = explode(';', $info['ip']);
                                                    $ip = '';
                                                    if (count($ip_usable) > 1) {
                                                        for ($i = 0; $i != count($ip_usable); $i++) {
                                                            if ($ip_usable[$i] != "127.0.0.1" && $ip_usable[$i] != "" && strpos($ip_usable[$i], '169.254.') === false) {
                                                                $ip = $ip_usable[$i];
                                                            }
                                                        }
                                                    } else {
                                                        $ip = $info["ip"];
                                                    }
                                                    if ($info["ip_buena"] != '') {
                                                        $ip = $info["ip_buena"];
                                                    }
                                                    if ($ip == '') {
                                                        $ip = 'SIN ASIGNAR';
                                                    }
                                                    echo '<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">' . $ip . '</div>';
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-network-wired fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Ubicación</div>
                                            <?
                                            $sql = "SELECT * FROM aulas WHERE id = " . $info['ubicacion'];
                                            $do = mysqli_query($link, $sql);
                                            $ubi = mysqli_fetch_assoc($do);
                                            $ubicacion = $ubi["nombre"];
                                            if (isset($_GET["edit"])) {
                                                echo '<form method="post" action="aparato.php?a=' . $info["id"] . '"><input type="hidden" value="' . $token . '" name="csrf_token"><input name="aula" value="'.$ubi["id"].'" type="hidden"><select name="ubicacion" type="text" class="form-control form-control-user h5 mb-0 mr-3 font-weight-bold text-gray-800" >'; 
                                                    $sql = "SELECT * FROM aulas";
                                                    $do = mysqli_query($link, $sql);
                                                    while($aula = mysqli_fetch_assoc($do)){
                                                        echo '<option '; if($aula["id"]==$info["ubicacion"]){echo 'selected';} echo'  value="'.$aula["id"].'">'.$aula["nombre"].'</option>';
                                                    }
                                                echo'</select><br><button class="btn btn-primary btn-user btn-block" type="submit">Guardar</button></form>';
                                            } else {
                                                echo '<div class="h5 mb-0 font-weight-bold text-gray-800">' . $ubicacion . '</div>';
                                            }
                                            ?>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-map-marked fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                            <div class="row">
                                <?
                                if ($info['tipo'] == 'ordenador' || $info['tipo'] == 'servidor') {

                                    echo ('<div class="col-lg-4 mb-3">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            CPU
                                            <div class="text-white-50 small">' . $info['cpu'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            RAM
                                            <div class="text-white-50 small">' . $info['ram'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            DISCO DURO
                                            <div class="text-white-50 small">' . $info['disco'] . '</div>
                                        </div>
                                    </div>
                                </div>');
                                }
                                ?>

                            </div>

                        </div>

                        <!-- Pie Chart -->

                    </div>
                    <!-- Pending error tickets calculator -->
                    <h2>Tickets de mantenimiento</h2>
                    <?
                    $sql = "SELECT * FROM ticket WHERE aparato='$aparato' AND estado = 'pendiente'";
                    $do = buscarbdo($sql);
                    $tickets = 0;
                    while ($row = mysqli_fetch_assoc($do)) {
                        $tickets++;
                    }
                    if ($tickets == 0) {
                        echo ('<div style="text-align:center">
                            <h4 style="text-align:center">Todo está en orden.</h4>
                            </div>');
                    }
                    ?>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4">



                            <!-- Color System -->
                            <div class="row">
                                <?
                                $aparato = $_GET['a'];
                                $sql = "SELECT * FROM ticket WHERE aparato=$aparato AND estado = 'pendiente'";
                                $do = buscarbdo($sql);
                                while ($row = mysqli_fetch_assoc($do)) {
                                    echo ('<a style="text-decoration:none;" href="ticket.php?t=' . $row['id'] . '"><div class="col-lg-4 mb-4">
                            <div class="card bg-danger text-white shadow">
                                <div class="card-body">
                                    ' . $row['tipo_error'] . '
                                    <div class="text-white-50 small">' . $row['descripcion'] . '</div>
                                </div>
                            </div></a>
                        </div>');
                                }

                                ?>


                            </div>

                        </div>
                    </div>
                    <div style="display: none;" id="info_adicional"></div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; CPSoftware 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <div class="modal fade" id="api" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">API</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="h5 modal-body">
                    <p>API Actual:</p>
                    <button onclick="updateapi()" class="btn btn-primary">Generar Token Nuevo</button>
                </div>

                <div id="holder-api" class="form-group col-lg-12">
                    <?php $sql = "SELECT * FROM token WHERE aparato = '$aparato'";
                    $do = mysqli_query($link, $sql);
                    $info_api = mysqli_fetch_assoc($do);

                    if ($do->num_rows > 0) {
                        echo '<p>Token: ' . $info_api["token"] . '</p>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <input type="hidden" name="cambioclave" id="">
                </div>
            </div>
        </div>
    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->

    <!-- Page level custom scripts -->

</body>

</html>

<script>
</script>
<script>
    var ip = '<?php echo $info["ip"]; ?>';
    var apid = '<?php echo $info["id"]; ?>';
    var info = document.getElementById('info_adicional');
    info.innerHTML = 'JS OK';

    function loaddata() {
        document.getElementById('loading_spinner').style.display = "";
        document.getElementById('loading').style.display = "";
        document.getElementById('icon').style.display = "none";
        document.getElementById('my_update_panel').innerHTML = 'Pingeando...';
        info.innerHTML = 'Funcion OK';
        $.ajax({
            type: 'post',
            url: 'ping.php',
            data: {
                sendping: ip,
                aparato: apid,
            },
            success: function(response) {
                info.innerHTML = 'Listo: ' + response;
                document.getElementById('loading_spinner').style.display = "none";
                document.getElementById('loading').style.display = "none";
                document.getElementById('icon').style.display = "";
                // We get the element having id of display_info and put the response inside it
                document.getElementById('my_update_panel').innerHTML = response;
            },
            error: function() {}
        });

    }
    loaddata();
</script>
<script>
    var tiempo = document.getElementById("tiempo");
    var updatetime = window.setInterval(function() {
        var apid = '<?php echo $info["id"]; ?>';
        $.ajax({
            type: 'post',
            url: 'update_time.php',
            data: {
                aparato: apid,
            },
            success: function(response) {
                tiempo.innerHTML = response;
            },
            error: function() {}
        });
        
    });
</script>
<script>
    var holderapi = document.getElementById("holder-api");
    var updateapi = function() {
        var apid = '<?php echo $info["id"]; ?>';
        $.ajax({
            type: 'post',
            url: 'generarapi.php',
            data: {
                aparato: apid,
            },
            success: function(response) {
                holderapi.innerHTML = response;
            },
            error: function() {}
        });
    };
</script>