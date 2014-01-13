<script>
$(document).ready(function()
{
	obligatorios("grupo");
});
</script>
	<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/grupo/insert',array('onkeyup' => 'limpiaformulario(this.id)', 'id' => 'grupo')); ?>
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
<div class="table table-striped">
	<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" title='requiere' name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripción</label></td>
		<td><textarea name="descripcion"><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('grupo')"/>
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/grupo/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>