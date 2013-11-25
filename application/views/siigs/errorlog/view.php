<?php
if(!empty($msgResult)) {
    echo '<h3>'.$msgResult.'</h3>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<table border="1">
        <tr><td><strong>Usuario</strong></td><td>'.$registro->usuario.'</td></tr>
        <tr><td><strong>Nombre</strong></td><td>'.$registro->nombre.' '.$registro->apellido_paterno.' '.$registro->apellido_materno.'</td></tr>
        <tr><td><strong>Fecha</strong></td><td>'.$registro->fecha_hora.'</td></tr>
        <tr><td><strong>Descripción</strong></td><td>'.$registro->descripcion.'</td></tr>
        <tr><td><strong>Entorno</strong></td><td>'.$registro->entorno.'</td></tr>
        <tr><td><strong>Controlador</strong></td><td>'.$registro->controlador.'</td></tr>
        <tr><td><strong>Acción</strong></td><td>'.$registro->accion.'</td></tr>
    </table>';
} else {
    echo 'ERROR: Registro no encontrado';
}
?>

<br />

<a href="<?php echo site_url().DIR_SIIGS; ?>/errorlog/">Regresar a listado</a>