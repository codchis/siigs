<?php
if(!empty($msgResult)) {
    echo '<h3>'.$msgResult.'</h3>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<table border="1">
        <tr><td><strong>MAC</strong></td><td>'.$registro->mac.'</td></tr>
        <tr><td><strong>Versi&oacute;n</strong></td><td>'.$registro->id_version.'</td></tr>
        <tr><td><strong>Ultima Actualizaci&oacute;n</strong></td><td>'.formatFecha($registro->ultima_actualizacion).'</td></tr>
        <tr><td><strong>Estado Tableta</strong></td><td>'.$registro->status.'</td></tr>
        <tr><td><strong>Tipo de censo</strong></td><td>'.$registro->tipo_censo.'</td></tr>
        <tr><td><strong>Unidad M&eacute;dica</strong></td><td>'.$registro->id_asu_um.'</td></tr>
    </table>';
} else {
    echo 'ERROR: Registro no encontrado';
}
?>

<br />

<a href="<?php echo site_url().DIR_SIIGS; ?>/menu/">Regresar a listado</a>