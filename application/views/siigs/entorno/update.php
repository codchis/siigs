<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php
if (!empty($entorno_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/entorno/update/'.$entorno_item->id) ?>
<div class="table table-striped">
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo $entorno_item->nombre; ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion"><?php echo $entorno_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td><label for="ip">IP</label></td>
		<td><input type="text" name="ip" value="<?php echo $entorno_item->ip; ?>"/></td>
	</tr>
	<tr>
		<td><label for="hostname">Hostname</label></td>
		<td><input type="text" name="hostname" value="<?php echo $entorno_item->hostname; ?>"/></td>
	</tr>
	<tr>
		<td><label for="directorio">Directorio</label></td>
		<td><input type="text" name="directorio" value="<?php echo $entorno_item->directorio; ?>"/></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $entorno_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/entorno/'" class="btn btn-primary" />
		<td>
	</tr>
</table>
</div>
</form>
<?php
}
else
{
echo "No se ha encontrado el elemento";
}
?>