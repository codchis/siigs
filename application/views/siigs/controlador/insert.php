<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php echo validation_errors(); ?>
<div class="table table-striped">
<?php echo form_open(DIR_SIIGS.'/controlador/insert') ?>
<table>
	<tr>
		<td><label for="nombre">Entorno</label></td>
		<td>  <?php  echo  form_dropdown('id_entorno', $entornos); ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion"><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="clase">Clase</label></td>
		<td><textarea name="clase"><?php echo set_value('clase', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="submit" name="submit" value="Guardar" class="btn btn-primary"/>
		<td>
	</tr>
</table>
</form>
</div>