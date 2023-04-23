<?php
// Incluye el archivo de la librería Flight
require 'flight/Flight.php';


// Registra una conexión a la base de datos usando PDO
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=api','root',''));

// Define una ruta que se activa cuando se accede a la URL "/"
// y muestra un mensaje de saludo en la página
Flight::route('/', function () {
    echo 'hola compa!';
});

// Define una ruta que se activa cuando se accede a la URL "/saludar"
// y muestra un mensaje de saludo en la página
Flight::route('/saludar', function () {
    echo 'hola man!';
});

// Define una ruta que se activa cuando se accede a la URL "/alumnos"
// mediante un método GET. Realiza una consulta a la base de datos
// y devuelve una respuesta JSON con los datos obtenidos
Flight::route('GET /alumnos', function () {
    $sentencia= Flight::db()->prepare("SELECT * FROM `alumnos`");
    $sentencia->execute();
    $datos=$sentencia->fetchAll();

    Flight::json($datos);
});

// Define una ruta que se activa cuando se accede a la URL "/alumnos"
// mediante un método POST. Recibe los datos del formulario enviado
// e inserta un nuevo registro en la tabla "alumnos" de la base de datos
Flight::route('POST /alumnos', function () {
    $nombres= Flight::request()->data->nombres;
    $apellidos= Flight::request()->data->apellidos;
    $sql="INSERT INTO `alumnos`(`nombres`, `apellidos`) VALUES (?,?)";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->execute();
    Flight::jsonp("Alumno agregado correctamente");
});

// Define una ruta que se activa cuando se accede a la URL "/alumnos"
// mediante un método DELETE. Recibe los datos del formulario enviado
// y elimina el registro correspondiente de la tabla "alumnos" en la base de datos
Flight::route('DELETE /alumnos', function () {
    $id= Flight::request()->data->id;
    $sql="DELETE FROM `alumnos` WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    Flight::jsonp("Alumno eliminado correctamente");
});

// Define una ruta que se activa cuando se accede a la URL "/alumnos"
// mediante un método PUT. Recibe los datos del formulario enviado
// y actualiza el registro correspondiente en la tabla "alumnos" de la base de datos
Flight::route('PUT /alumnos', function () {
    $id= Flight::request()->data->id;
    $nombres= Flight::request()->data->nombres;
    $apellidos= Flight::request()->data->apellidos;
    $sql="UPDATE `alumnos` SET `nombres`=?,`apellidos`=? WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$id);
    $sentencia->execute();
    Flight::jsonp("Alumno actualizado correctamente");
});

// Se define una ruta para la URL /alumnos/@id que permite leer un registro de la tabla alumnos
// El parámetro @id en la URL se pasa como argumento a la función anónima
Flight::route('GET /alumnos/@id', function ($id) {
    $sentencia= Flight::db()->prepare("SELECT * FROM `alumnos` WHERE id=?");
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});

// Inicia el framework y se encarga de procesar las solicitudes
// que llegan al servidor y enviar las respuestas correspondientes.
Flight::start();
