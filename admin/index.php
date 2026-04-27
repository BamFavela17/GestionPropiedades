<?php 

    require '../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth) {
        header('Location: /');
    }

    // Importar la conexión
    require '../includes/config/database.php';
    $db = conectarDB();

    // Escribir el Query
    $query = "SELECT * FROM propiedades";

    // Consultar la BD 
    $resultadoConsulta = $db->query($query);


    // Muestra mensaje condicional
    $resultado = $_GET['resultado'] ?? null;


    

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id) {

            // Eliminar el archivo
            $stmt = $db->prepare("SELECT imagen FROM propiedades WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $propiedad = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($propiedad && file_exists('../imagenes/' . $propiedad['imagen'])) {
                unlink('../imagenes/' . $propiedad['imagen']);
            }
    
            // Eliminar la propiedad
            $stmt = $db->prepare("DELETE FROM propiedades WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $stmt->execute();

            if($resultado) {
                header('location: /admin?resultado=3');
            }
        }

        
    }

    // Incluye un template

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if( intval( $resultado ) === 1): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif( intval( $resultado ) === 2 ): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
        <?php elseif( intval( $resultado ) === 3 ): ?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php endif; ?>

        <a href="/admin/vendedores/crear.php" class="boton boton-amarillo">Nuevo(a) Vendedor</a>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        <a href="/admin/vendedores/index.php" class="boton boton-amarillo">Ver Vendedores</a>

        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody> <!-- Mostrar los Resultados -->
                <?php while( $propiedad = $resultadoConsulta->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"> </td>
                    <td>$ <?php echo $propiedad['precio']; ?></td>
                    <td>
                        <form method="POST" class="w-100">

                            <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">

                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

<?php 


    incluirTemplate('footer');
?>