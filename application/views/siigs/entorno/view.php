<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<h2><?php echo $title; ?></h2>
<?php
if (!empty($entorno_item))
{
	echo '<div class="table table-striped">
	<table>
        <tr><td colspan=2><strong>[ '.$entorno_item->nombre.' ]</strong></td></tr>
        <tr><td><strong>Descripci&oacute;n</strong></td><td>'.$entorno_item->descripcion.'</td></tr>
        <tr><td><strong>IP</strong></td><td>'.$entorno_item->ip.'</td></tr>
        <tr><td><strong>Hostname</strong></td><td>'.$entorno_item->hostname.'</td></tr>
        <tr><td><strong>Directorio</strong></td><td>'.$entorno_item->directorio.'</td></tr>
        </table></div>';
}
else
{
echo "No se ha encontrado el elemento";
}
?>
<a href="<?php echo site_url().DIR_SIIGS; ?>/entorno/" class="btn btn-primary">Regresar al listado</a>