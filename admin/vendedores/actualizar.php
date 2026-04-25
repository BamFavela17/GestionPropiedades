<?php 
    require '../../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth) {
        header('Location: /');
    }

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header('Location: /admin');
    }

    require '../../includes/config/database.php';
    $db = conectarDB();

    // Obtener datos del vendedor
    $consulta = "SELECT * FROM vendedores WHERE id = {$id}";
    $resultado = mysqli_query($db, $consulta);
    $vendedor = mysqli_fetch_assoc($resultado);

    $errores = [];

    $nombre = $vendedor['nombre'];
    $apellido = $vendedor['apellido'];
    $telefono = $vendedor['telefono'];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = mysqli_real_escape_string($db, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($db, $_POST['apellido']);
        $telefono = mysqli_real_escape_string($db, $_POST['telefono']);

        if(!$nombre) {
            $errores[] = "El nombre es obligatorio";
        }
        if(!$apellido) {
            $errores[] = "El apellido es obligatorio";
        }
        if(!$telefono) {
            $errores[] = "El teléfono es obligatorio";
        }
        if(!preg_match('/[0-9]{10}/', $telefono)) {
            $errores[] = "Formato de teléfono no válido (10 dígitos)";
        }

        if(empty($errores)) {
            $query = "UPDATE vendedores SET nombre = '{$nombre}', apellido = '{$apellido}', telefono = '{$telefono}' WHERE id = {$id}";
            $resultado = mysqli_query($db, $query);

            if($resultado) {
                header('Location: /admin/vendedores/index.php?resultado=2');
            }
        }
    }

    incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Vendedor(a)</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST">
        <fieldset>
            <legend>Información General</legend>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre Vendedor(a)" value="<?php echo $nombre; ?>">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" placeholder="Apellido Vendedor(a)" value="<?php echo $apellido; ?>">
        </fieldset>

        <fieldset>
            <legend>Información Extra</legend>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Teléfono Vendedor(a)" value="<?php echo $telefono; ?>">
        </fieldset>

        <input type="submit" value="Guardar Cambios" class="boton boton-verde">
    </form>
</main>

<?php 
    incluirTemplate('footer');
?>