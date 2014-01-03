<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	if (isset($group_item)) {
?>
<div class="table table-striped">
<table>
	<tr>
		<td><label for="id">Id</label></td>
		<td><?php echo $group_item->id ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><?php echo $group_item->nombre ?></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripción</label></td>
		<td><?php echo $group_item->descripcion ?></td>
	</tr>
</table>
</div>
<?php } ?>
<a href="<?php echo site_url().DIR_SIIGS; ?>/grupo/" class="btn btn-primary">Regresar al listado</a>