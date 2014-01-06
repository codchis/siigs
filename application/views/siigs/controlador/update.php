<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 <?php
if (!empty($controlador_item))
{
?>
<?php echo validation_errors(); ?>
<div class="table table-striped">
<?php echo form_open(DIR_SIIGS.'/controlador/update/'.$controlador_item->id) ?>
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo $controlador_item->nombre; ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion"><?php echo $controlador_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td><label for="clase">Clase</label></td>
		<td><textarea name="clase"><?php echo $controlador_item->clase; ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $controlador_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" class="btn btn-primary"/>
		</td>
	</tr>
</table>
</form>
<?php
}
else
{
echo '<div class="error">Registro no encontrado</div>';
}
?>
</div>