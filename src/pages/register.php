<?php
    // Base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    require '../../includes/funciones.php';

    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        if(!$email) {
            $errores[] = "El email es obligatorio o no es válido";
        }
        if(!$password) {
            $errores[] = "El password es obligatorio";
        }

        if(empty($errores)) {
            // Hashear el password
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Revisar si el usuario ya existe
            $query = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if($usuario) {
                $errores[] = "El usuario ya existe";
            } else {
                // Insertar el usuario
                $query = "INSERT INTO usuarios (email, password) VALUES (:email, :password)";
                $stmt = $db->prepare($query);
                $resultado = $stmt->execute([
                    'email' => $email,
                    'password' => $passwordHash
                ]);

                if($resultado) {
                    // Redireccionar al login tras registro exitoso
                    header('Location: /src/pages/login.php');
                }
            }
        }
    }

    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Registro de Usuario</h1>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST">
            <fieldset>
                <legend>Email y Password</legend>

                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Tu Email" id="email">

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu Password" id="password">
            </fieldset>

            <input type="submit" value="Registrar Usuario" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>