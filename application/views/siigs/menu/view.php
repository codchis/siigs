<?php
if(!empty($msgResult)) {
    echo '<h3>'.$msgResult.'</h3>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<table border="1">
        <tr><td><strong>Raíz</strong></td><td>'.$registro->nombre_raiz.'</td></tr>
        <tr><td><strong>Padre</strong></td><td>'.$registro->nombre_padre.'</td></tr>
        <tr><td><strong>Ruta</strong></td><td>'.$registro->ruta.'</td></tr>
        <tr><td><strong>Nombre</strong></td><td>'.$registro->nombre.'</td></tr>
        <tr><td><strong>Entorno</strong></td><td>'.$registro->nombre_entorno.'</td></tr>
        <tr><td><strong>Controlador</strong></td><td>'.$registro->nombre_controlador.'</td></tr>
    </table>';
} else {
    echo 'ERROR: Registro no encontrado';
}
?>

<br />

<a href="<?php echo site_url().DIR_SIIGS; ?>/menu/">Regresar a listado</a>