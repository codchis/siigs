<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($controlador_item))
{
echo '<h2>[ '.$controlador_item->nombre.' ]</h2>';
echo '<h5>Descripcion: '.$controlador_item->descripcion.'</h5><br/>';
echo '<h5>Clase: '.$controlador_item->clase.'</h5><br/>';
}
else
	echo "No se ha encontrado el elemento";