<?php

require '../../includes/funciones.php';
$auth = estaAutenticado();

if (!$auth) {
    header('Location: /');
}

// Importar la conexión
require '../../includes/config/database.php';
$db = conectarDB();

// Escribir el Query
$queryVendedores = "SELECT * FROM vendedores";

// Consultar la BD 
$resultadoVendedores = mysqli_query($db, $queryVendedores);



// Muestra mensaje condicional
$resultado = $_GET['resultado'] ?? null;




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    $tipo = $_POST['tipo'];

    if ($id) {
        if ($tipo === 'vendedor') {
            // Eliminar el vendedor
            $query = "DELETE FROM vendedores WHERE id = {$id}";
            $resultado = mysqli_query($db, $query);

            if ($resultado) {
                header("location: /admin/vendedores/index.php?resultado=3");
            }
        }
    }
}

// Incluye un template

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Vendedores</h1>
    <?php if (intval($resultado) === 1): ?>
        <p class="alerta exito">Vendedor(a) Registrado Correctamente</p>
    <?php elseif (intval($resultado) === 2): ?>
        <p class="alerta exito">Vendedor(a) Actualizado Correctamente</p>
    <?php elseif (intval($resultado) === 3): ?>
        <p class="alerta exito">Vendedor(a) Eliminado Correctamente</p>
    <?php endif; ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
    <a href="/admin/vendedores/crear.php" class="boton boton-amarillo">Nuevo(a) Vendedor</a>
    <a href="/admin/index.php" class="boton boton-verde">Ver Propiedades</a>

    <table class="propiedades">
        <thead style="background-color: #E08709;">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($vendedor = mysqli_fetch_assoc($resultadoVendedores)): ?>
                <tr>
                    <td><?php echo $vendedor['id']; ?></td>
                    <td><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></td>
                    <td><?php echo $vendedor['telefono']; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $vendedor['id']; ?>">
                            <input type="hidden" name="tipo" value="vendedor">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="/admin/vendedores/actualizar.php?id=<?php echo $vendedor['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php

// Cerrar la conexion
mysqli_close($db);

incluirTemplate('footer');
?>