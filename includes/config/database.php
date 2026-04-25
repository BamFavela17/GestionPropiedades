<?php 

function conectarDB() : mysqli {
    // Activa el modo de excepciones para MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $db = mysqli_connect(
            'sql208.infinityfree.com', 
            'if0_41749033', 
            'KigdNzMFJ1os5aZ', 
            'if0_41749033_bienesraices_crud'
        );

        // Forzar el set de caracteres a UTF-8 para evitar problemas con tildes y ñ
        mysqli_set_charset($db, 'utf8');

        return $db;

    } catch (mysqli_sql_exception $e) {
        echo "Error: No se pudo conectar a la base de datos.";
        // En desarrollo puedes usar: exit($e->getMessage());
        exit;
    }
}