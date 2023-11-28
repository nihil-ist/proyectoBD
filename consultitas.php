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

//HAVING
// 1- Obtener el nombre y el teléfono de los proveedores que han suministrado más de 5 productos
echo "<br><strong> 1. Obtener el nombre y el teléfono de los proveedores que han suministrado más de 5 productos </strong><br>";
$query = "SELECT p.nombreProveedor AS 'Nombre del proveedor', p.telefono AS 'Teléfono del proveedor' FROM proveedor p JOIN proveer pr ON p.IDProveedor = pr.IDProveedorP GROUP BY p.nombreProveedor, p.telefono HAVING COUNT(pr.IDProductoP) > 5; ";
$result = mysqli_query($conexion, $query);
$columnas = array("Nombre del proveedor", "Teléfono del proveedor");
imprimirTabla($result, $columnas);

// 2- Obtener el género musical que solo se ha mostrado una vez entre los clientes:
echo "<br><strong> 2. Obtener el género musical que solo se ha mostrado una vez entre los clientes: </strong><br>";
$query = "SELECT GeneroMusical FROM  Cliente GROUP BY GeneroMusical HAVING COUNT(*) = 1; ";
$result = mysqli_query($conexion, $query);
$columnas = array("Genero Musical");
imprimirTabla($result, $columnas);

// 3- Obtener el nombre y el precio de los productos que tienen un precio mayor que el promedio de todos los productos.
echo "<br><strong> 4. Obtener el nombre y el precio de los productos que tienen un precio mayor que el promedio de todos los productos. </strong><br>";
$query = " SELECT p.nombreP AS 'Nombre del producto', p.precio AS 'Precio del producto'
            FROM producto p
            GROUP BY p.nombreP, p.precio
            HAVING p.precio > (SELECT AVG(precio) FROM producto);
            ";
$result = mysqli_query($conexion, $query);
$columnas = array("Nombre del producto", "Precio del producto");
imprimirTabla($result, $columnas);

//4- Obtener el nombre y el puesto de los empleados que han vendido al menos un producto de cada tipo (Disco, Vinil o Casete)
echo "<br><strong> 2. Obtener el género musical que solo se ha mostrado una vez entre los clientes: </strong><br>";
$query = " SELECT e.nombre AS 'Nombre del empleado', e.puesto AS 'Puesto del empleado'
            FROM empleado e
            JOIN vender v ON e.RFCEmpleado = v.RFCEmpleadoV
            JOIN producto p ON v.IDProductoV = p.IDProducto
            GROUP BY e.nombre, e.puesto
            HAVING COUNT(DISTINCT p.tipoProducto) = 3;
            ";
$result = mysqli_query($conexion, $query);
$columnas = array("Nombre del empleado","Puesto del empleado");
imprimirTabla($result, $columnas);

//5- Obtener el artista y el número de canciones de los productos que tienen más de 10 canciones y cuyo género es Indie o Pop, usando el alias “Productos musicales”.
echo "<br><strong> 2. Obtener el género musical que solo se ha mostrado una vez entre los clientes: </strong><br>";
$query = " SELECT
            p.artista,
            p.noCanciones AS 'Productos musicales'
            FROM producto p
            WHERE p.genero = 'Indie' OR p.genero = 'Pop'
            GROUP BY
            p.artista,
            p.noCanciones
            HAVING p.noCanciones > 10;
            ";
$result = mysqli_query($conexion, $query);
$columnas = array("Artista","Productos musicales");
imprimirTabla($result, $columnas);

mysqli_close($conexion);
?>
