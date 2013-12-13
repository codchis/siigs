<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($regla_item))
{
echo '<h2>[Regla para aplicacion de vacuna]</h2>';
echo '<h5>Vacuna: '.$regla_item->vacuna.'</h5><br/>';
echo '<h5>Tipo de aplicaci&oacute;n: '.$regla_item->aplicacion.'</h5><br/>';
echo '<h5>Desde (dias): '.$regla_item->desde.'</h5><br/>';
echo '<h5>Hasta (dias): '.$regla_item->hasta.'</h5><br/>';
echo '<h5>Vacuna Previa: '.$regla_item->previa.'</h5><br/>';
}
else
{
	echo "No se ha encontrado el elemento";
}