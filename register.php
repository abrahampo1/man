<?php
include("database.php");
$usuario = "";
$mail="";
$clave="";
$clave_r='';
$nombre = "";
$apellidos = "";
print('xd');
if(isset($_POST["mail"]))
{
    $mail = $_POST["mail"];
    print('pim');
}

if(isset($_POST["pass"]) && isset($_POST["user"])){
    $usuario = $_POST["user"];
    $clave = $_POST["pass"];
    $clave_r = $_POST["pass_r"];
    print('pam');
    if($clave != $clave_r)
    {
        header('Location: error.php?e=3');
    }
    $clave_secreta = password_hash($clave, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM tecnicos WHERE user = '$usuario'";
    $do = mysqli_query($link, $sql);
    $i = false;
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    while($result = mysqli_fetch_assoc($do)){
        $i = true;
    }
    if($i == false)
    {
        print('tomalacasitos');
        $sql = "INSERT INTO `tecnicos` (`id`, `user`, `clave`, `nombre`, `apellidos`) VALUES (NULL, '$usuario', '$clave_secreta', '$nombre', '$apellidos')";
        if(!$do = mysqli_query($link, $sql)){
            echo(mysqli_error($link));
        }else
        {
            header("location: ok.php?o=0");
        }
        
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

    <title>Registra Tecnicos</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5 text-center">
            <div class="card-body p-0 text-center">
                <!-- Nested Row within Card Body -->
                <div class="text-center">
                    <div class="col-lg-12 text-center">
                        <div class="p-5 text-center">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Registra un Tecnico</h1>
                            </div>
                            <form class="user" method="post" action="">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input required type="text" class="form-control form-control-user" name="nombre"
                                            placeholder="Nombre">
                                    </div>
                                    <div class="col-sm-6">
                                        <input required type="text" class="form-control form-control-user" name="apellidos"
                                            placeholder="Apellidos">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input required type="email" class="form-control form-control-user"
                                            name="mail" placeholder="Mail">
                                    </div>
                                    <div class="col-sm-6">
                                        <input required type="text" class="form-control form-control-user"
                                            name="user" placeholder="Usuario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input required type="password" class="form-control form-control-user"
                                            name="pass" placeholder="Clave">
                                    </div>
                                    <div class="col-sm-6">
                                        <input required type="password" class="form-control form-control-user"
                                            name="pass_r" placeholder="Repita la clave">
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary btn-user btn-block" value="Registrar">
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>