<?php
    $servidor= 'localhost:33066';
    $cuenta='root';
    $password='';
    $bd= 'havenrecords';

    $conexion =mysqli_connect($servidor,$cuenta,$password,$bd);
    if(mysqli_connect_errno()){die("Error en la conexion");}
    else{//la conexion se ha hecho
        if(isset($_POST['submit'])){
            //se obtienen datos del formulario
            $eliminar=$_POST['eliminar'];

            //estructuramos el query para posteriormente hacerse la consulta
            $sql = "DELETE FROM usuarios WHERE id='$eliminar'";
            $conexion->query($sql);
            if($conexion->affected_rows>=1){
                echo "<br> registro borrado <br>";
            }
        }
        $sql = "SHOW TABLES";
        $resultado = $conexion->query($sql);
        $tablas = array();
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_row()) {
                $tablas[] = $fila[0];
            }
        }

        $tabla_seleccionada = null;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tabla'])) {
            $tabla_seleccionada = $_POST["tabla"];
        }
        $conexion->close();
        //seguimos con los datos, se muestran todos los que siguen existiendo de la tabla si es que eliminamos uno
    }
?>
<div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Seleccione una tabla:</h2>
        <select name="tabla" onchange="this.form.submit()">
            <?php foreach ($tablas as $tabla): ?>
                <option value="<?php echo $tabla; ?>" <?php if ($tabla_seleccionada === $tabla) echo 'selected="selected"'; ?>><?php echo $tabla; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
    <form action="">
    <?php if($tabla_seleccionada){
        $conexion =mysqli_connect($servidor,$cuenta,$password,$bd);
        if(mysqli_connect_errno()){die("Error en la conexion");}
        $sql = "select * from $tabla_seleccionada";//hacemos cadena con la sentencia mysql que consulta todo el contenido de la tabla
        $resultado = $conexion -> query($sql); //aplicamos sentencia
        $campos = $resultado->fetch_fields();
        $sql_columnas = "SHOW COLUMNS FROM $tabla_seleccionada";
        $resultado_columnas = $conn->query($sql_columnas);
        $tablaSQL.="<table><tr>";
        while ($fila = $resultado_columnas->fetch_assoc()) {
            $tablaSQL.= "<label>" . $fila['Field'] . ":</label>";
            echo "<input type='text' name='" . $fila['Field'] . "'><br>";
        }

        foreach ($resultado as $fila) {
            // Imprime los datos de la fila
            foreach ($campos as $campo) {
                echo $fila[$campo->name]."\t";
            }
            $tablaSQL.="</table>";
            echo"$tablaSQL <br>";
        }
        /*if ($resultado -> num_rows){ //si la consulta genera registros
            $salida='<table>';
               while( $fila = $resultado -> fetch_assoc() ){ //recorremos los registros obtenidos de la tabla
                   echo '<option value="'.$fila["id"].'">'.$fila["nombre"].'</option>';
                   //proceso de concatenacion de datos
                   $salida.= '<tr>';
                   $salida.= '<td>'. $fila['id'] . '</td>';
                   $salida.= '<td>'. $fila['nombre'] . '</td>';
                   $salida.= '<td>'. $fila['cuenta'] . '</td>';
                   $salida.= '<td>'. $fila['contrasena'] . '</td>';
                   $salida.= '</tr>';
                   }//fin while   
                   $salida.= '</table>';
        }         */  
    }
        ?>

        </form>
</div>
<!DOCTYPE html>	
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>