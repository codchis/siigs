<?php
if(!empty($msgResult)) {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    
    echo '<div class="table table-striped">
        <table>
        <tr><td><strong>Usuario</strong></td><td>'.$registro->usuario.'</td></tr>
        <tr><td><strong>Nombre</strong></td><td>'.$registro->nombre.' '.$registro->apellido_paterno.' '.$registro->apellido_materno.'</td></tr>
        <tr><td><strong>Fecha</strong></td><td>'.$registro->fecha_hora.'</td></tr>
        <tr><td><strong>Parámetros</strong></td><td>'.$registro->parametros.'</td></tr>
        <tr><td><strong>Entorno</strong></td><td>'.$registro->entorno.'</td></tr>
        <tr><td><strong>Controlador</strong></td><td>'.$registro->controlador.'</td></tr>
        <tr><td><strong>Acción</strong></td><td>'.$registro->accion.'</td></tr>
    </table></div>';
} else {
     echo '<div class="'.($clsResult ? $clsResult : 'info').'">ERROR: Registro no encontrado</div>';
}
?>
