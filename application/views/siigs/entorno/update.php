<script>
$(document).ready(function(){
    obligatorios('frm_insert');
});
</script>

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
<?php echo form_open(DIR_SIIGS.'/entorno/update/'.$entorno_item->id, array('id'=>'frm_insert', 'onkeyup' => 'limpiaformulario(\'frm_insert\')')) ?>
    
    <div class="table table-striped">
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div> 
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' required value="<?php echo $entorno_item->nombre; ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion" title='requiere' required><?php echo $entorno_item->descripcion; ?></textarea></td>
	</tr>
	<tr>
		<td><label for="ip">IP</label></td>
		<td><input type="text" name="ip" title='requiere' required value="<?php echo $entorno_item->ip; ?>"/></td>
	</tr>
	<tr>
		<td><label for="hostname">Hostname</label></td>
		<td><input type="text" name="hostname" title='requiere' required value="<?php echo $entorno_item->hostname; ?>"/></td>
	</tr>
	<tr>
		<td><label for="directorio">Directorio</label></td>
		<td><input type="text" name="directorio" title='requiere' required value="<?php echo $entorno_item->directorio; ?>"/></td>
	</tr>
	<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $entorno_item->id; ?>"/>
                <button type="submit" name="submit" value="Guardar" class="btn btn-primary"  onclick="return validarFormulario('frm_insert')">Guardar<i class="icon-hdd"></i></button>
                <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/entorno/'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
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