<html>

<head>

    <?php
    /// AÑADE EL CODIGO NECESARIO PARA PODER ACCEDER AL CONTENIDO DEL FICHERO statistics.php
    include "statistics.php";
    ?>

</head>


<body>
    <form method="POST">
        <table>
            <tr>
                <td>
                    <label>Nombre del juego: </label>
                </td>
                <td>
                    <input type="text" name="nombre" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Nombre del equipo: </label>
                </td>
                <td>
                    <input type="text" name="equipo" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Numero de jugadores: </label>
                </td>
                <td>
                    <input type="number" name="numJugadores" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Puntos conseguidos: </label>
                </td>
                <td>
                    <input type="number" name="puntuacion" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Partida ganada: (si/no) </label>
                </td>
                <td>
                    <input type="c" name="ganada" />
                </td>
            </tr>
            <tr>
                <td>
                    <label>Fecha: </label>
                </td>
                <td>
                    <input type="date" name="fecha" />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Guardar" />
                </td>
            </tr>
            <tr>
                <input type="hidden" name="fallos" value=<?php echo montarlo() ?> />
            </tr>

        </table>
    </form>


    <?php

    if (isset($_POST["nombre"]) && isset($_POST["equipo"]) && isset($_POST["numJugadores"]) && isset($_POST["puntuacion"]) && isset($_POST["ganada"]) && isset($_POST["fecha"])) {
        if ($_POST["nombre"] != "" && $_POST["equipo"] != "" && $_POST["numJugadores"] != "" && $_POST["puntuacion"] != "" && $_POST["ganada"] != "" && $_POST["fecha"] != "") {
            if (comprobarNombre($_POST["nombre"])) {
                if (comprobarMiembros($_POST["numJugadores"])) {
                    if (comprobarPuntuacion($_POST["puntuacion"])) {
                        if (comprobarFecha($_POST["fecha"])) {
                            if (comprobarGanado($_POST["ganada"])) {
                                //AQUI YA ESTÁ TODO COMPROBADO;
                                $ganar = false;
                                if (strtolower($_POST["ganada"]) == "si") {
                                    $ganar = true;
                                }
                                $db = new DBManager();
                                $stat = new Statistics($_POST["nombre"], $_POST["equipo"], $_POST["numJugadores"], $_POST["puntuacion"], $ganar, $_POST["fecha"]);
                                $db->insertStatistics($stat);
                            } else {
                                echo "La partida o está ganada (si), o no está ganada (no). No pongas cosas raras";
                            }
                        } else {
                            echo "Introduce la fecha correctamente";
                        }
                    } else {
                        echo "La puntuacion no puede ser negativa";
                    }
                } else {
                    echo "El numero de jugadores tiene que ser 3, 4 o 5";
                }
            } else {
                echo "Debes de introducir el nombre del juego correctamente";
            }
        } else {
            echo "Debes de rellenar los campos!!";
        }
    }

    function comprobarNombre($nombre)
    {
        $nombre = strtolower($nombre);
        $array = ["lol", "wow", "valorant", "fortnite", "minecraft"];
        for ($i = 0; $i < 5; $i++) {
            if ($array[$i] === $nombre) {
                return true;
            }
        }
        return false;
    }

    function comprobarMiembros($miembros)
    {
        if ($miembros == 3 || $miembros == 4 || $miembros == 5) {
            return true;
        } else {
            return false;
        }
    }

    function comprobarPuntuacion($puntuacion)
    {
        if ($puntuacion >= 0) {
            return true;
        } else {
            return false;
        }
    }

    function comprobarFecha($fecha)
    {
        $dia = explode("-", $fecha);
        if (checkdate($dia[1], $dia[2], $dia[0])) {
            return true;
        } else {
            return false;
        }
    }

    function comprobarGanado($ganado)
    {
        $ganado = strtolower($ganado);
        if ($ganado === "si" || $ganado === "no") {
            return true;
        } else {
            return false;
        }
    }

    function esSi($ganado)
    {
        $ganado = strtolower($ganado);
        if (comprobarGanado($ganado)) {
            if ($ganado === "si") {
                return true;
            } else {
                return false;
            }
        }
    }


    function porcentajeVictoriasJuegos($nombre)
    {
        $db = new DBManager();
        $statsArray = $db->getStatistics();
        $contadorLol = 0;
        $contadorWow = 0;
        $contadorVal = 0;
        $contadorFor = 0;
        $contadorMin = 0;
        $partidas = 0;
        $porcentajes = [];

        foreach ($statsArray as $stats) {
            if ($stats->getTeamName() === $nombre && strtolower($stats->getGame()) === "lol" && $stats->getWon()) {
                $contadorLol++;
            } else if ($stats->getTeamName() === $nombre && strtolower($stats->getGame()) === "wow" && $stats->getWon()) {
                $contadorWow++;
            } else if ($stats->getTeamName() === $nombre && strtolower($stats->getGame()) === "valorant" && $stats->getWon()) {
                $contadorVal++;
            } else if ($stats->getTeamName() === $nombre && strtolower($stats->getGame()) === "fortnite" && $stats->getWon()) {
                $contadorFor++;
            } else if ($stats->getTeamName() === $nombre && strtolower($stats->getGame()) === "minecraft" && $stats->getWon()) {
                $contadorMin++;
            }
            $partidas++;
        }
        if ($contadorLol != 0) {
            $contadorLol = ($partidas / $contadorLol) * 100;
        }
        if ($contadorWow != 0) {
            $contadorWow = ($partidas / $contadorWow) * 100;
        }
        if ($contadorVal != 0) {
            $contadorVal = ($partidas / $contadorVal) * 100;
        }
        if ($contadorFor != 0) {
            $contadorFor = ($partidas / $contadorFor) * 100;
        }
        if ($contadorMin != 0) {
            $contadorMin = ($partidas / $contadorMin) * 100;
        }

        $porcentajes[0] = $contadorLol;
        $porcentajes[1] = $contadorWow;
        $porcentajes[2] = $contadorVal;
        $porcentajes[3] = $contadorFor;
        $porcentajes[4] = $contadorMin;

        return $porcentajes;
    }

    function porcentajeVictorias($nombre)
    {
        $db = new DBManager();
        $statsArray = $db->getStatistics();
        $contador = 0;
        $partidas = 0;

        foreach ($statsArray as $stats) {
            if ($stats->getTeamName() === $nombre && $stats->getWon()) {
                $contador++;
            }
            $partidas++;
        }
        $contador = ($partidas / $contador) * 100;

        return $contador;
    }

    function mediaPuntuacionGeneral()
    {
        $db = new DBManager();
        $statsArray = $db->getStatistics();
        $mediaLol = 0;
        $mediaWow = 0;
        $mediaVal = 0;
        $mediaFor = 0;
        $mediaMin = 0;
        $partidasLol = 0;
        $partidasWow = 0;
        $partidasVal = 0;
        $partidasFor = 0;
        $partidasMin = 0;
        $media = [];

        foreach ($statsArray as $stats) {
            if (strtolower($stats->getGame()) === "lol") {
                $mediaLol += $stats->getScore();
                $partidasLol++;
            } else if (strtolower($stats->getGame()) === "wow") {
                $mediaWow += $stats->getScore();
                $partidasWow++;
            } else if (strtolower($stats->getGame()) === "valorant") {
                $mediaVal += $stats->getScore();
                $partidasVal++;
            } else if (strtolower($stats->getGame()) === "fortnite") {
                $mediaFor += $stats->getScore();
                $partidasFor++;
            } else if (strtolower($stats->getGame()) === "minecraft") {
                $mediaMin += $stats->getScore();
                $partidasMin++;
            }
        }
        $mediaLol = ($mediaLol / $partidasLol) * 100;
        $mediaWow = ($mediaWow / $partidasWow) * 100;
        $mediaVal = ($mediaVal / $partidasVal) * 100;
        $mediaFor = ($mediaFor / $partidasFor) * 100;
        $mediaMin = ($mediaMin / $partidasMin) * 100;
        $media[0] = $mediaLol;
        $media[1] = $mediaWow;
        $media[2] = $mediaVal;
        $media[3] = $mediaFor;
        $media[4] = $mediaMin;

        return $media;
    }



    function montarlo($fallosAux = 0)
    {
        if (isset($_POST["fallos"])) {
            $fallos = $_POST["fallos"];
        } else {
            $fallos = $fallosAux;
        }
        return $fallos;
    }





    // EJEMPLO DE UNAI:
    /*
    $db = new DBManager();

    $stat = new Statistics("LoL", "unai", "4", 56, true, date("Y-m-d", strtotime("2020-03-01")));
    $db->insertStatistics($stat);
    $db->borrar();

    $statsArray = $db->getStatistics();

    echo "<ul>";
    foreach ($statsArray as $stats) {
        echo "<li>$stats</li>";
    }
    echo "<ul>";

*/

    // LO QUE YO VOY A USAR

    $db = new DBManager();
    $statsArray = $db->getStatistics();

    echo "<ul>";
    foreach ($statsArray as $stats) {
        echo "<li>$stats</li>";
    }
    echo "<ul>";

    ?>



</body>

</html>