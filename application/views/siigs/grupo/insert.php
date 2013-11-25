<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
    echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/grupo/insert') ?>
<table border="1">
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripción</label></td>
		<td><textarea name="descripcion"><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" /><td>
	</tr>
</table>
</form>