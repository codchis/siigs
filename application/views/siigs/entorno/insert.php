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

<?php echo form_open(DIR_SIIGS.'/entorno/insert', array('onkeyup' => 'limpiaformulario(this.id)','id'=>'frm_insert')) ?>
    
    <div class="table table-striped">    
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div> 
    
<table>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' required value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="descripcion">Descripcion</label></td>
		<td><textarea name="descripcion" title='requiere' required><?php echo set_value('descripcion', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="ip">IP</label></td>
		<td><input type="text" name="ip" title='requiere' required value="<?php echo set_value('ip', ''); ?>"/></td>
	</tr>
	<tr>
		<td><label for="hostname">Hostname</label></td>
		<td><input type="text" name="hostname" title='requiere' required value="<?php echo set_value('hostname', ''); ?>"/></td>
	</tr>
	<tr>
		<td><label for="directorio">Directorio</label></td>
		<td><input type="text" name="directorio" title='requiere' required value="<?php echo set_value('directorio', ''); ?>"/></td>
	</tr>
	<tr>
		<td colspan=2>
                    <button type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('frm_insert')">Guardar<i class="icon-hdd"></i></button>
                <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/entorno/'" class="btn btn-primary" >Cancelar<i class="icon-arrow-left"></i></button><td>
	</tr>
</table>
</div>
</form>