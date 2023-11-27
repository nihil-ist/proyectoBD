<?php
$servername = "localhost:3310"; // Cambiar si tu servidor MySQL está en otro lugar
$username = "root"; // Cambiar por tu nombre de usuario de MySQL
$password = ""; // Cambiar por tu contraseña de MySQL
$dbname = "havenrecords"; // Cambiar por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener las tablas disponibles en la base de datos
$sql = "SHOW TABLES";
$result = $conn->query($sql);

// Guardar nombres de las tablas en un array
$tables = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
}

// Cerrar conexión
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
        <select name="tabla">
            <?php foreach ($tables as $table): ?>
                <option value="<?php echo $table; ?>"><?php echo $table; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Ver tabla">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si se envió el formulario, obtener la tabla seleccionada
        $selected_table = $_POST["tabla"];

        // Mostrar la tabla seleccionada
        echo "<h2>Tabla: $selected_table</h2>";

        // Conexión nuevamente para mostrar datos de la tabla seleccionada
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Mostrar los registros de la tabla seleccionada
        $sql_data = "SELECT * FROM $selected_table";
        $result_data = $conn->query($sql_data);

        if ($result_data->num_rows > 0) {
            echo "<table border='1'><tr>";
            while ($fieldinfo = $result_data->fetch_field()) {
                echo "<th>" . $fieldinfo->name . "</th>";
            }
            echo "</tr>";

            while ($row = $result_data->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . $value . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "La tabla seleccionada no tiene registros.";
        }

        // Cerrar conexión
        $conn->close();
    }
    ?>
</body>
</html>
