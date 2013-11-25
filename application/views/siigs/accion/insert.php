<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/accion/insert') ?>
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion"><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="metodo">M&eacute;todo</label></td>
		<td><textarea name="metodo"><?php echo set_value('metodo', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" /><td>
	</tr>
</table>
</form>