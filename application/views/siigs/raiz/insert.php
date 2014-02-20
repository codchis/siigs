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
<?php echo form_open(DIR_SIIGS.'/raiz/insert', array('onkeyup' => 'limpiaformulario(this.id)','id'=>'frm_insert')) ?>
<div class="table table-striped">
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div> 
<table>
	<tr>
		<td><label for="descripcion">Descripci&oacute;n</label></td>
		<td><textarea name="descripcion" title='requiere' required><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
            <button type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('frm_insert')">Guardar<i class="icon-hdd"></i></button>
            <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/raiz/'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
        <td>
	</tr>
</table>
</form>
</div>