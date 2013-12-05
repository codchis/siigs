<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
 <?php
if (!empty($catalogo_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_TES.'/cie10/update/'.$catalogo_item->id) ?>
<table>
	<tr>
		<td><label for="nombre">Código</label></td>
		<td><label><?php echo $catalogo_item->cie10; ?></label></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion"><?php echo $catalogo_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $catalogo_item->id; ?>"/>
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