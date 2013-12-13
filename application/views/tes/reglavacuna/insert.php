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
        if ($('input[name=tipo_aplicacion]').is(':checked') && $('select[name=id_vacuna_previa]').val()*1<=0)
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
echo $msgResult.'<br /><br />';
 ?>
<?php echo validation_errors(); ?>
<?php echo form_open(DIR_TES.'/reglavacuna/insert' , array('id'=>'frm_insert')) ?>
<table>
	<tr>
		<td><label for="nombre">Vacuna:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_vacuna', $vacunas); ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Vacuna previa:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_vacuna_previa', $vacunas); ?></td>
	</tr>
	<tr>
		<td><label for="descripcion">Tipo de Aplicaci&oacute;n:</label></td>
                <td><input type="radio" name="tipo_aplicacion" value="nacimiento" /> A partir de nacimiento</td>
                <td><input type="radio" name="tipo_aplicacion" value="previa"/> A partir de vacuna previa</td>
	</tr>
	<tr>
                <td><label for="descripcion">D&iacute;as de aplicaci&oacute;n:</label></td>
                <td>Desde:<br/><input type="text" name="aplicacion_inicio" id="dia_inicio"/></td>
                <td>Hasta:<br/><input type="text" name="aplicacion_fin" id="dia_fin"/></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" /><td>
	</tr>
</table>
</form>