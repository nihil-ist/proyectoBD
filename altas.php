<?php
//Si es necesario le quitan la extension del puerto del localhost
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
        <?php
        $conn = new mysqli($nombre_servidor, $nombre_usuario, $contraseña, $nombre_base_datos);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql_columnas = "SHOW COLUMNS FROM $tabla_seleccionada";
        $resultado_columnas = $conn->query($sql_columnas);

        echo "<h2>Insertar nuevo registro en $tabla_seleccionada</h2>";
        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "<input type='hidden' name='tabla' value='$tabla_seleccionada'>";
        while ($fila = $resultado_columnas->fetch_assoc()) {
            echo "<label>" . $fila['Field'] . ":</label>";
            echo "<input type='text' name='" . $fila['Field'] . "'><br>";
        }
        echo "<input type='submit' name='submit' value='Insertar'>";
        echo "</form>";

        $conn->close();
        ?>
    <?php endif; ?>
</body>
</html>
