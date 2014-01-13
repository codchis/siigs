<script>
$(document).ready(function(){
    obligatorios('frm_insert');
});
</script>

<script>
$(document).ready(function(){
    $('#frm_insert').submit(function(){
        if($('select[name=id_vacuna]').val()*1<=0)
        {
            alert('Debe elegir una vacuna antes de guardar');
            return false;            
        }
        
        if ($('#dia_inicio').val()*1 >= $('#dia_fin').val()*1)     
        {
            alert('El dia inicial de la aplicación debe ser mayor al dia final');
            return false;
        }
        if ($('input[name=tipo_aplicacion]').is(':checked') == false)
        {
            alert('Debe elegir el tipo de aplicación');
            return false;
        }
        if ($('input[name=tipo_aplicacion]:checked').val() == 'previa' && $('select[name=id_vacuna_previa]').val()*1<=0)
        {
            alert('Debe elegir el tipo de vacuna previa para esta regla');
            return false;
        }
    });
});
</script>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_SIIGS.'/reglavacuna/insert', array('id'=>'frm_insert', 'onclick' => 'limpiaformulario(this.id)','onkeyup' => 'limpiaformulario(this.id)')) ?>
<div class="table table-striped">
    
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
<table>
	<tr>
		<td><label for="nombre">Vacuna:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_vacuna', $vacunas, '', 'title=\'requiere\''); ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Vacuna previa:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_vacuna_previa', $vacunas); ?></td>
	</tr>
	<tr>
		<td><label for="descripcion">Tipo de Aplicaci&oacute;n:</label></td>
                <td><input type="radio" name="tipo_aplicacion" value="nacimiento"  title='requiere' required/> A partir de nacimiento</td>
                <td><input type="radio" name="tipo_aplicacion" value="previa" title='requiere' required/> A partir de vacuna previa</td>
	</tr>
	<tr>
                <td><label for="descripcion">D&iacute;as de aplicaci&oacute;n:</label></td>
                <td>Desde:<br/><input type="text" name="aplicacion_inicio" id="dia_inicio" title='requiere' required/></td>
                <td>Hasta:<br/><input type="text" name="aplicacion_fin" id="dia_fin" title='requiere' required/></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('frm_insert')"/>
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/reglavacuna/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>