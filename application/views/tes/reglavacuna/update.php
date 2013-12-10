<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
 <?php
if (!empty($accion_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/accion/update/'.$accion_item->id) ?>
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo $accion_item->nombre; ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion"><?php echo $accion_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td><label for="metodo">M&eacute;todo</label></td>
		<td><textarea name="metodo"><?php echo $accion_item->metodo; ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $accion_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" />
		<td>
	</tr>
</table>
</form>
<?php
}
else
{
echo "No se ha encontrado el elemento";
}
?>