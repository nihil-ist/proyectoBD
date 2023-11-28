<?php
$nombre_servidor = "localhost:3310";
$nombre_usuario = "root";
$contraseña = "";
$nombre_base_datos = "havenrecords";

$conn = new mysqli($nombre_servidor, $nombre_usuario, $contraseña, $nombre_base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SHOW TABLES";
$resultado = $conn->query($sql);

$tablas = array();
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_row()) {
        if (strpos($fila[0], 'rel_') !== 0) {
            $tablas[] = $fila[0];
        }
    }
}

$tabla_seleccionada = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tabla'])) {
    $tabla_seleccionada = $_POST["tabla"];

    $sql_columnas = "SHOW COLUMNS FROM $tabla_seleccionada";
    $resultado_columnas = $conn->query($sql_columnas);

    // Preparar los nombres de las columnas para la inserción
    $column_names = [];
    while ($fila = $resultado_columnas->fetch_assoc()) {
        $column_names[] = $fila['Field'];
    }

    // Insertar datos en la tabla seleccionada
    if (isset($_POST['submit'])) {
        $values = [];
        foreach ($column_names as $column) {
            if (isset($_POST[$column])) {
                $values[] = "'" . $conn->real_escape_string($_POST[$column]) . "'";
            } else {
                $values[] = "NULL";
            }
        }

        $sql_insert = "INSERT INTO $tabla_seleccionada (" . implode(', ', $column_names) . ") VALUES (" . implode(', ', $values) . ")";
        if ($conn->query($sql_insert) === TRUE) {
            echo "<p>Registro insertado correctamente en la tabla $tabla_seleccionada.</p>";
        } else {
            echo "<p>Error al insertar el registro: " . $conn->error . "</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ver y dar de alta en tablas</title>
</head>
<body>
    <h2>Seleccione una tabla:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <select name="tabla" onchange="this.form.submit()">
            <?php foreach ($tablas as $tabla): ?>
                <option value="<?php echo $tabla; ?>" <?php if ($tabla_seleccionada === $tabla) echo 'selected="selected"'; ?>><?php echo $tabla; ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($tabla_seleccionada): ?>
        <h2>Insertar nuevo registro en <?php echo $tabla_seleccionada; ?></h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="tabla" value="<?php echo $tabla_seleccionada; ?>">
            <?php foreach ($column_names as $column): ?>
                <label><?php echo $column; ?>:</label>
                <input type="text" name="<?php echo $column; ?>"><br>
            <?php endforeach; ?>
            <input type="submit" name="submit" value="Insertar">
        </form>
    <?php endif; ?>
</body>
</html>
