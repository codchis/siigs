<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($regla_item))
{
	echo '<div class="table table-striped">
	<table>
        <tr><td colspan=2><h2>[Regla para aplicacion de vacuna]</h2></strong></td></tr>
        <tr><td><strong>Vacuna</strong></td><td>'.$regla_item->vacuna.'</td></tr>
        <tr><td><strong>Tipo de aplicaci&oacute;n</strong></td><td>'.$regla_item->aplicacion.'</td></tr>
        <tr><td><strong>Desde (dias)</strong></td><td>'.$regla_item->desde.'</td></tr>
        <tr><td><strong>Hasta (dias)</strong></td><td>'.$regla_item->hasta.'</td></tr>
        <tr><td><strong>Vacuna Previa</strong></td><td>'.$regla_item->previa.'</td></tr>
        <tr><td><strong>Via Vacuna</strong></td><td>'.$regla_item->via_vacuna.'</td></tr>
        <tr><td><strong>Dosis</strong></td><td>'.$regla_item->dosis.'</td></tr>
        <tr><td><strong>Regi&oacute;n</strong></td><td>'.$regla_item->region.'</td></tr>
        <tr><td><strong>Observaciones de la Regi&oacute;n</strong></td><td>'.$regla_item->observacion_region.'</td></tr>
        <tr><td><strong>Esquema Completo</strong></td><td>'.(($regla_item->esq_com == 1) ? 'Si' : 'No').'</td></tr>';
            
        if (($regla_item->esq_com == 1)) 
            echo '<tr><td><strong>Orden Esq. Comp.</strong></td><td>'.$regla_item->orden_esq_com.'</td></tr>';
        
        echo '<tr><td><strong>Alergias</strong></td><td>'.$regla_item->alergias.'</td></tr>
        <tr><td><strong>Forzar a periodo de aplicaci&oacute;n</strong></td><td>'.(($regla_item->forzar_aplicacion) ? "SI" : "NO" ).'</td></tr>
        </table></div>';
}
else
{
	echo "No se ha encontrado el elemento";
}