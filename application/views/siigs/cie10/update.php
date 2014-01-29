<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 <?php
if (!empty($catalogo_item))
{
?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/cie10/update/'.$catalogo_item->id) ?>
<div class="table table-striped">
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
                <button type="submit" name="submit" class="btn btn-primary">Guardar<i class="icon-hdd"></i></button>
                <button type="button" name="cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/cie10/'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
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