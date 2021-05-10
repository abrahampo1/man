<?
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM tecnicos WHERE id = $user_id";
$search = mysqli_query($link, $sql);
$user_info = mysqli_fetch_assoc($search);
?>
<!--Si borras este mensaje el codigo será auto destruido-->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <a href="/"><i class="fas fa-home"></i></a>
    <form method="get" action="/" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar aparato..." value="<?php if (isset($_GET['b'])) {
                                                                                                                        echo $_GET['b'];
                                                                                                                    } ?>" aria-label="Search" name="b" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search" method="GET" target="_blank" action="index.php">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar aparato..." aria-label="Search" value="<?php if (isset($_GET['b'])) {
                                                                                                                                                        echo $_GET['b'];
                                                                                                                                                    } ?>" aria-describedby="basic-addon2" name="b" id="b">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <?
                //Sistema de tickets

                $sql = "SELECT * FROM ticket WHERE tecnico = $user_id AND estado = 'pendiente'";
                $conteo_tickets = 0;
                if ($ticket_bd = mysqli_query($link, $sql)) {

                    while ($ticket = mysqli_fetch_assoc($ticket_bd)) {
                        $conteo_tickets++;
                    }

                    if ($conteo_tickets == 0) {
                        echo '
                            </a>';
                    } else {
                        echo '<span class="badge badge-danger badge-counter">+' . $conteo_tickets . '</span>
                    </a>';
                    }
                } else {
                    $conteo_tickets = 0;
                    echo '
                    </a>';
                }

                ?>

                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Centro de Alertas
                    </h6>
                    <?
                    if ($conteo_tickets > 0) {
                        $sql = "SELECT * FROM ticket WHERE tecnico = $user_id and estado = 'pendiente'";
                        $ticket_bd = mysqli_query($link, $sql);
                        while ($ticket = mysqli_fetch_assoc($ticket_bd)) {
                            $aparato = $ticket["aparato"];
                            $sql = "SELECT * FROM ordenadores WHERE id = $aparato";
                            $do = mysqli_query($link, $sql);
                            $info_aparato = mysqli_fetch_assoc($do);
                            $fecha_creacion = date('Y-m-d H:i:s', $ticket["fecha"]);
                            echo '<a class="dropdown-item d-flex align-items-center" href="ticket.php?t=' . $ticket["id"] . '">
                        <div class="mr-3">
                            <div class="icon-circle bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">' . $fecha_creacion . '</div>
                            <span class="font-weight-bold">' . $info_aparato["nombre"] . ' - ' . $ticket["tipo_error"] . '</span>
                        </div>
                    </a>';
                        }
                    } else {
                        echo '<a class="dropdown-item d-flex align-items-center">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                        <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>
                    <div>
                        <span class="font-weight-bold">Todo en orden</span>
                    </div>
                </a>';
                    }
                    ?>

                    <a class="dropdown-item text-center small text-gray-500" href="#">Todas las alertas</a>
                </div>
        </li>

        <!-- Nav Item - Messages -->


        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <? echo $user_info["nombre"] ?>
                </span>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#ajustes" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="add_ordenador">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Añadir equipo
                </a>
                <a class="dropdown-item" href="actividad.php">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Actividad
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cerrar Sesión
                </a>
            </div>
        </li>

    </ul>
    <div class="modal fade" id="ajustes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajustes</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="h5 modal-body">
                    <p>Telegram</p>
                    <?php
                    if ($user_info["telegram"] == "") {
                        echo "Desconectado.";
                    } else {
                        echo "<p>ChatID: " . $user_info["telegram"] . "</p>
                        <button class='btn btn-danger'>Desconectar.</button>";
                    }
                    ?>

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
</nav>
<!-- End of Topbar -->