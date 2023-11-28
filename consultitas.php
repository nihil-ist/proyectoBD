<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "havenrecords";

$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// come caca coco
function imprimirTabla($result, $columnas) {
    echo "<table border='1'>";
    echo "<tr>";
    foreach ($columnas as $columna) {
        echo "<th>$columna</th>";
    }
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }

    echo "</table><br>";
}

// 1. Listar el género musical favorito de los clientes
echo "<strong>1. Listar el género musical favorito de los clientes:</strong><br>";
$query = "SELECT generoMusical FROM cliente";
$result = mysqli_query($conexion, $query);
$columnas = array("Género Musical");
imprimirTabla($result, $columnas);

// 2. Listar el email de los clientes
echo "<br><strong>2. Listar el email de los clientes:</strong><br>";
$query = "SELECT email FROM cliente";
$result = mysqli_query($conexion, $query);
$columnas = array("Email");
imprimirTabla($result, $columnas);

// 3. Listar todos los datos de los empleados
echo "<br><strong>3. Listar todos los datos de los empleados:</strong><br>";
$query = "SELECT RFCEmpleado, nombre, puesto FROM empleado";
$result = mysqli_query($conexion, $query);
$columnas = array("RFC Empleado", "Nombre", "Puesto");
imprimirTabla($result, $columnas);

// 4. Listar el número de canciones de los productos que tengan como artista a Taylor Swift
echo "<br><strong>4. Listar el número de canciones de los productos que tengan como artista a Taylor Swift:</strong><br>";
$query = "SELECT noCanciones FROM producto WHERE artista = 'Taylor Swift'";
$result = mysqli_query($conexion, $query);
$columnas = array("Número de Canciones");
imprimirTabla($result, $columnas);

// 5. Listar los productos de tipo vinil y que tengan un costo mayor a 3000
echo "<br><strong>5. Listar los productos de tipo vinil y que tengan un costo mayor a 3000:</strong><br>";
$query = "SELECT idProducto, artista, noCanciones, genero, tipoProducto FROM producto WHERE tipoProducto = 'vinil' AND precio > 3000";
$result = mysqli_query($conexion, $query);
$columnas = array("ID Producto", "Artista", "Número de Canciones", "Género", "Tipo de Producto");
imprimirTabla($result, $columnas);

mysqli_close($conexion);
?>
