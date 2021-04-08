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
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <style>
    a { text-decoration: none; }
    a:link {
  text-decoration: none;
}
  </style>
    <title>CPM - Home</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
               <?include("topbar.php");?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tickets de mantenimiento</h1>
                    </div>
                    <div class="row">
                    <?php
                        include('database.php');
                        $sql = 'SELECT * FROM ticket';
                        if(isset($_GET["b"]))
                        {
                            $busc = $_GET['b'];
                            $sql = "SELECT * FROM ticket WHERE nombre LIKE '%$busc%' or id LIKE '$busc' or usuario LIKE '%$busc%' or aparato LIKE '%$busc%' or tipo_error LIKE '%$busc%' or descripcion LIKE '%$busc%' or tecnico LIKE '%$busc%' or estado LIKE '%$busc%'";
                        }
                        $busqueda = mysqli_query($link, $sql);
                        while($fila = mysqli_fetch_assoc($busqueda))
                        {
                        echo '<a style="text-decoration:none;" href="ticket.php?t='.$fila['id'].'"><div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            '.$fila['tipo_error'].'</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">'.$fila['estado'].'</div>
                                        <div class="h9 mb-0 font-weight-bold text-gray-800">'.$fila['usuario'].'</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-warning" fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                        </div>';
                        }
                        ?>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>