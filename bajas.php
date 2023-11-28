<?php
    $servidor= 'localhost:33066';
    $cuenta='root';
    $password='';
    $bd= 'havenrecords';

    $conexion =mysqli_connect($servidor,$cuenta,$password,$bd);
    if(mysqli_connect_errno()){die("Error en la conexion");}
    else{//la conexion se ha hecho
        $tabla_seleccionada = null;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tabla'])) {
            $tabla_seleccionada = $_POST["tabla"];
        }
        
        if(isset($_POST['submit'])){
            $eliminar = $_POST['cEliminar'];
            $primary = $_POST['idT'];
            foreach ($eliminar as $id) {
                $sql = "DELETE FROM $tabla_seleccionada WHERE $primary='$id'";
                $conexion->query($sql);
            }
            echo "<br> Registros borrados <br>";
        }
        $sql = "SHOW TABLES";
        $resultado = $conexion->query($sql);
        $tablas = array();
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_row()) {
                $tablas[] = $fila[0];
            }
        }
        $conexion->close();
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <?php if($tabla_seleccionada){
        $conexion =mysqli_connect($servidor,$cuenta,$password,$bd);
        if(mysqli_connect_errno()){die("Error en la conexion");}
        $sql_data = "SELECT * FROM $tabla_seleccionada";
        $result_data = $conexion->query($sql_data);
        $bandera = false;
        if ($result_data->num_rows > 0) {
            echo "<table border='1' style='text-align:center;'><tr>";
            while ($fieldinfo = $result_data->fetch_field()) {
                if ($bandera === false){
                    echo "<th>Eliminar</th>";
                    $bandera = true;
                }
                echo "<th>" . $fieldinfo->name . "</th>";
            }
            echo "</tr>";
            $bandera = false;
            while ($row = $result_data->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    if ($bandera === false){
                        echo "<td><input type='checkbox' name='cEliminar[]' value=$value onchange='checkSubmitButton()'/></td>";
                        $bandera = true;
                    }
                    echo "<td>" . $value . "</td>";
                }
                $bandera = false;
                echo "</tr>";
            }
            $sql = "SHOW KEYS FROM $tabla_seleccionada WHERE Key_name = 'PRIMARY'";
            $resultado = $conexion->query($sql);
            $clave = $resultado->fetch_assoc();
            $nombre_clave_primaria = $clave['Column_name'];
            echo "</table> <input type='hidden' name='tabla' value='$tabla_seleccionada'>
            <input type='hidden' name='idT' value='$nombre_clave_primaria'>
            <input type='submit' name='submit' value='Borrar registros' disabled>";
        }
    }
?>
        </form>
        <script>
            function checkSubmitButton() {
            let checkedCheckboxes = document.querySelectorAll('input[name="cEliminar[]"]:checked');
            document.querySelector('input[name="submit"]').disabled = checkedCheckboxes.length === 0;
            }
        </script>
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