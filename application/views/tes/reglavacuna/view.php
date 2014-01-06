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
        </table></div>';
}
else
{
	echo "No se ha encontrado el elemento";
}