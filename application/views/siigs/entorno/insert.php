<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/entorno/insert') ?>
<div class="table table-striped">
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion"><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="ip">IP</label></td>
		<td><input type="text" name="ip" value="<?php echo set_value('ip', ''); ?>"/></td>
	</tr>
	<tr>
		<td><label for="hostname">Hostname</label></td>
		<td><input type="text" name="hostname" value="<?php echo set_value('hostname', ''); ?>"/></td>
	</tr>
	<tr>
		<td><label for="directorio">Directorio</label></td>
		<td><input type="text" name="directorio" value="<?php echo set_value('directorio', ''); ?>"/></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/entorno/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>