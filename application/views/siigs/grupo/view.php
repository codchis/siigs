<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	if (isset($group_item)) {
?>
<table border="1">
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
<?php } ?>