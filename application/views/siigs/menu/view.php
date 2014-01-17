<?php
if(!empty($msgResult)) {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<div class="table table-striped">
        <table>
        <tr><td><strong>Raíz</strong></td><td>'.$registro->nombre_raiz.'</td></tr>
        <tr><td><strong>Padre</strong></td><td>'.$registro->nombre_padre.'</td></tr>
        <tr><td><strong>Ruta</strong></td><td>'.$registro->ruta.'</td></tr>
        <tr><td><strong>Nombre</strong></td><td>'.$registro->nombre.'</td></tr>
        <tr><td><strong>Entorno</strong></td><td>'.$registro->nombre_entorno.'</td></tr>
        <tr><td><strong>Controlador</strong></td><td>'.$registro->nombre_controlador.'</td></tr>
    </table></div>';
} else {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">ERROR: Registro no encontrado</div>';
}
?>