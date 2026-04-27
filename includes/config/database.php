<?php 

function conectarDB() : PDO {
    try {
        // Carga manual de .env (ajustado a la ubicación del archivo)
        $envFile = __DIR__ . '/../templates/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                putenv(trim($name) . "=" . trim($value));
            }
        }

        // Configuración de Supabase (PostgreSQL)
        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $dbName = getenv('DB_NAME');
        $port = getenv('DB_PORT');
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbName;sslmode=require";

        // Crear la conexión con PDO
        $db = new PDO($dsn, $user, $pass);
        
        // Configurar para que lance excepciones en caso de error
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Forzar codificación UTF-8 en PostgreSQL
        $db->exec("SET NAMES 'UTF8'");

        return $db;

    } catch (PDOException $e) {
        echo "Error: No se pudo conectar a la base de datos.";
        exit;
    }
}