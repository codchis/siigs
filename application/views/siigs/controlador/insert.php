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
<?php echo form_open(DIR_SIIGS.'/controlador/insert', array('id'=>'frm_insert', 'onclick' => 'limpiaformulario(this.id)','onkeyup' => 'limpiaformulario(this.id)')) ?>

    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>  
<table>
	<tr>
		<td><label for="nombre">Entorno</label></td>
		<td>  <?php  echo  form_dropdown('id_entorno', $entornos, '', 'title=\'requiere\''); ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' required value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion" title='requiere' required><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="clase">Clase</label></td>
		<td><textarea name="clase" title='requiere' required><?php echo set_value('clase', ''); ?></textarea></td>
	</tr>
	<tr>
		<td colspan=2>
            <button type="submit" name="submit"   value="Guardar"  class="btn btn-primary" onclick="return validarFormulario('frm_insert')">Guardar <i class="icon-hdd"></i></button>
            <button type="button" name="cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/controlador/'">Cancelar <i class=" icon-arrow-left"></i></button>
		<td>
	</tr>
</table>
</form>
</div>