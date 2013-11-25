<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($entorno_item))
{
echo '<h2>[ '.$entorno_item->nombre.' ]</h2>';
echo '<h5>Descripci&oacute;n: '.$entorno_item->descripcion.'</h5><br/>';
echo '<h5>IP: '.$entorno_item->ip.'</h5><br/>';
echo '<h5>Hostname: '.$entorno_item->hostname.'</h5><br/>';
echo '<h5>Directorio: '.$entorno_item->directorio.'</h5><br/>';
}
else
{
echo "No se ha encontrado el elemento";
}