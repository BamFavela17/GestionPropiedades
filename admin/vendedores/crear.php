<?php 
    require '../../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth) {
        header('Location: /');
    }

    require '../../includes/config/database.php';
    $db = conectarDB();

    $errores = [];

    $nombre = '';
    $apellido = '';
    $telefono = '';

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
            $errores[] = "Formato de teléfono no válido";
        }

        if(empty($errores)) {
            $query = "INSERT INTO vendedores (nombre, apellido, telefono) VALUES ('{$nombre}', '{$apellido}', '{$telefono}')";
            $resultado = mysqli_query($db, $query);

            if($resultado) {
                header('Location: /admin/vendedores/index.php?resultado=1');
            }
        }
    }

    incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Registrar Vendedor(a)</h1>

    <a href="/admin/vendedores/index.php" class="boton boton-verde">Volver</a>

    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/admin/vendedores/crear.php">
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

        <input type="submit" value="Registrar Vendedor(a)" class="boton boton-verde">
    </form>
</main>

<?php 
    incluirTemplate('footer');
?>