<style>
    h1 {
        font-family: 'Ubuntu', sans-serif;
        text-align: center;
    }

    h2 {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        text-align: center;
    }

    .card {
        display: none;
        justify-content: center;
        flex-direction: row;
    }

    input {
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        border: 0px solid black;
        padding: 20px;
    }

    button {
        text-decoration: none;
        background-color: black;
        border: 0pc solid black;
        color: white;
        font-family: 'Ubuntu', sans-serif;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .articulo {
        width: auto;
    }
</style>



<html>

<head>
    <title>Hola Instalador</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
</head>

<div class="card" id="step1">
    <div style="text-align: center; ">
        <img src="img/logo i+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
        <div>
            <h1>¡Hola!</h1>
            <h2>Está a punto de comenzar una nueva instalación</h2>
            <div style="margin-top: 40px;">
                    <input type="text" id="name" name="nombre" placeholder="Escribe aqui tu nombre"><br>
                    <button onclick="next()">Siguiente</button>

            </div>
        </div>
    </div>
</div>

<div class="card" id="step2">
    <div style="text-align: center; ">
    <img src="img/logo i+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
        <div>
            <h1 id="display_name1">Abraham</h1>
            <h2>¿En donde lo vas a instalar?</h2>
            <div style="margin-top: 40px;">
                    <input type="text" placeholder="Escribe aqui el equipo">
                    <br>
                    <button onclick="next()">Siguiente</button>

            </div>
        </div>
    </div>
</div>

<div class="card" id="step3">
    <div style="text-align: center; ">
    <img src="img/logo i+d.png" alt="" style="border-radius: 10px;margin-bottom: 60px" width="300px" height="auto">
        <div>
            <h1 id="display_name2">Abraham</h1><h1>estás a punto de instalar lo siguiente:</h1>
            <h2>¿Es correcto?</h2>
            <div style="margin-top: 40px;">
                <div>
                    <div class="articulo">
                        <img src="img/inventario/29.png" alt="" width="200px" height="auto">
                        <h2>SSD KINGSTON 240GB</h2>
                        <h2>Cantidad: 1</h2>
                    </div>
                </div>
                <form action="" method="post">
                <input type="hidden" name="name" id="name_final">
                <input type="hidden" name="equipo" id="equipo_final">
                    <button>Comenzar</button>
                </form>

            </div>
        </div>
    </div>
</div>



</html>

<script>
var nombre;
var equipo;
var step = 1;
window.onload = function(){
    document.getElementById("step"+step).style.display = "flex";
}

function next(){
    nombre = document.getElementById("name").value;
    equipo = document.getElementById("equipo").value;
    document.getElementById("display_name1").innerHTML = nombre;
    document.getElementById("display_name2").innerHTML = nombre + ",";
    document.getElementById("name_final").value = nombre;
    document.getElementById("equipo_final").value = equipo;
    document.getElementById("step"+step).style.display = "none";
    step++;
    document.getElementById("step"+step).style.display = "flex";
}
    
</script>