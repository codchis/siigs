<?php
if(!empty($msgResult)) {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<div class="table table-striped">
        <table>
        <tr><td><strong>Descripci&oacute;n</strong></td><td>'.$registro->descripcion.'</td></tr>
        <tr><td><strong>Fecha de inicio</strong></td><td>'.formatFecha($registro->fecha_inicio, 'd-m-Y').'</td></tr>
        <tr><td><strong>Fcha de fin</strong></td><td>'.formatFecha($registro->fecha_fin, 'd-m-Y').'</td></tr>
    </table></div>';
} else {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">ERROR: Registro no encontrado</div>';
}
?>
