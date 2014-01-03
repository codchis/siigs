<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/grupo/insert') ?>
<div class="table table-striped">
	<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripción</label></td>
		<td><textarea name="descripcion"><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/grupo/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>