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
<?php echo validation_errors(); ?>
<div class="table table-striped">
<?php echo form_open(DIR_SIIGS.'/accion/insert', array('onkeyup' => 'limpiaformulario(this.id)','id'=>'frm_insert')) ?>
    
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>    
    
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' required value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion" title='requiere' required><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="metodo">M&eacute;todo</label></td>
		<td><textarea name="metodo" title='requiere' required><?php echo set_value('metodo', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
                    <input type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('frm_insert')"/>
                    <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/accion/'" class="btn btn-primary" />
                <td>
	</tr>
</table>
</form>
</div>