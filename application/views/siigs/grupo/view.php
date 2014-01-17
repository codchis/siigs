<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	if (count($group_item) == 0) echo 'Registro no encontrado.<br><br>'; else {
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
	<tr>
	<td><label for="entorno">Entornos vinculados</label></td>
	<td><?php if (isset($entornos)) foreach ($entornos as $entorno_item): echo $entorno_item->entorno.'<br>'; endforeach; ?></td>
	</tr>
</table>
</div>
<?php } ?>