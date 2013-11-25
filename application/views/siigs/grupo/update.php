<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
    echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/grupo/update/'.$group_item->id) ?>
<table border="1">
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><?php echo $group_item->nombre ?></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripción</label></td>
		<td><textarea name="descripcion"><?php echo $group_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="id" value="<?php echo $group_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" />
		<td>
	</tr>
</table>
</form>