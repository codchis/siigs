<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($accion_item))
{
echo '<h2>[ '.$accion_item->nombre.' ]</h2>';
echo '<h5>Descripci&oacute;n: '.$accion_item->descripcion.'</h5><br/>';
echo '<h5>M&eacute;todo: '.$accion_item->metodo.'</h5><br/>';
}
else
{
	echo "No se ha encontrado el elemento";
}