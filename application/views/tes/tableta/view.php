<?php
if(!empty($msgResult)) {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<div class="table table-striped">
        <table>
        <tr><td><strong>MAC</strong></td><td>'.$registro->mac.'</td></tr>
        <tr><td><strong>Versi&oacute;n</strong></td><td>'.$registro->id_version.'</td></tr>
        <tr><td><strong>Ultima Actualizaci&oacute;n</strong></td><td>'.formatFecha($registro->ultima_actualizacion).'</td></tr>
        <tr><td><strong>Estado Tableta</strong></td><td>'.$registro->status.'</td></tr>
        <tr><td><strong>Tipo de censo</strong></td><td>'.$registro->tipo_censo.'</td></tr>
        <tr><td><strong>Unidad M&eacute;dica</strong></td><td>'.$registro->id_asu_um.'</td></tr>
    </table></div>';
} else {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">ERROR: Registro no encontrado</div>';
}
?>
