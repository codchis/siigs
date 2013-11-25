<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($raiz_item))
{
echo '<h5>Descripci&oacute;n: '.$raiz_item->descripcion.'</h5><br/>';
}
else
{
	echo "No se ha encontrado el elemento";
}