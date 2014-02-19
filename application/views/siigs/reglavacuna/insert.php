<link href="/resources/x-editable/jqueryui-editable.css" rel="stylesheet">
<link href="/resources/x-editable/jquery-ui-1.10.1.custom.css" rel="stylesheet">
<script src="/resources/x-editable/jqueryui-editable.js"></script>


<script>
$(function(){

   $.fn.editable.defaults.url = null; 
   $.fn.editable.defaults.mode = 'popup';
  
 
   $('#list_alergias').editable({
       pk: 1,
       limit: 3,
       target: '#alergias',
       source: [
           <?php
           foreach($alergias as $item)
               echo "{value:".$item->id.",text:'".$item->descripcion."'},";
           ?>
       ],
       tokenSeparators: [",", " "]
    }); 
       
});    
    
$(document).ready(function(){
    obligatorios('frm_insert');
});
</script>

<script>
$(document).ready(function(){
    
    $('#esq_com').change(function(){
        $('#td_orden').css({'visibility':(($(this).is(':checked')) ? 'visible' : 'hidden')});
    });
    
    $('#frm_insert').submit(function(){
        
        if ($('input[name=esq_com]').is(':checked') == true)
        {
            if($('select[name=orden_esq_com]').val()*1<=0)
            {
                alert('Debe elegir el orden de la vacuna en el esquema completo');
                return false;            
            }
        }
        
        $('#alergias').val($('#list_alergias').attr("data-value"));
//        alert($('#alergias').val());
//        return false;
        
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
//        if ($('input[name=tipo_aplicacion]').is(':checked') == false)
//        {
//            alert('Debe elegir el tipo de aplicación');
//            return false;
//        }
//        if ($('input[name=tipo_aplicacion]:checked').val() == 'previa' && $('select[name=id_vacuna_previa]').val()*1<=0)
//        {
//            alert('Debe elegir el tipo de vacuna previa para esta regla');
//            return false;
//        }
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
	<!--tr>
		<td><label for="descripcion">Tipo de Aplicaci&oacute;n:</label></td>
                <td><input type="radio" name="tipo_aplicacion" value="nacimiento"  title='requiere' required/> A partir de nacimiento</td>
                <td><input type="radio" name="tipo_aplicacion" value="previa" title='requiere' required/> A partir de vacuna previa</td>
	</tr-->
	<tr>
                <td><label for="descripcion">D&iacute;as de aplicaci&oacute;n<br/>a partir del nacimiento:</label></td>
                <td>Desde:<br/><input type="text" name="aplicacion_inicio" id="dia_inicio" title='requiere' value="<?php echo set_value('aplicacion_inicio', ''); ?>" required/></td>
                <td>Hasta:<br/><input type="text" name="aplicacion_fin" id="dia_fin" title='requiere' value="<?php echo set_value('aplicacion_fin', ''); ?>" required/></td>
	</tr>
        <tr>
		<td><label for="id_via_vacuna">V&iacute;a Vacuna:</label></td>
                <td colspan="2"><?php  echo  form_dropdown('id_via_vacuna', $vias_vacuna, '', 'title=\'requiere\''); ?></td>
	</tr>
        <tr>
		<td><label for="dosis">Dosis:</label></td>
                <td colspan="2"><input type="text" name="dosis" id="dosis"  value="<?php echo set_value('dosis', ''); ?>"/></td>
	</tr>
        <tr>
		<td><label for="region">Regi&oacute;n:</label></td>
                <td colspan="2"><input type="text" name="region" id="region" value="<?php echo set_value('region', ''); ?>"/></td>
	</tr>
        <tr>
		<td><label for="region">Observaciones de la Regi&oacute;n:</label></td>
                <td colspan="2"><textarea type="text" name="observacion_region" id="observacion_region" rows="5" ><?php echo set_value('observacion_region', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="esq_com" id="esq_com"/> Esquema Completo</td>
                <td colspan="2" id="td_orden" style="visibility:hidden">Orden: <?php  echo  form_dropdown('orden_esq_com', $orden); ?></td>
	</tr>
        <tr>         
            <td>Alergias</td>
            <td colspan="2">
                <a href="#" id="list_alergias" data-type="checklist" data-value="" data-title="Seleccione Alergias" class="editable editable-click">Seleccione Alergias</a>
            </td>
        </tr>
        <tr>         
            <td colspan="3"><input type="checkbox" name="forzar_aplicacion" id="forzar_aplicacion"> &nbsp;Forzar al periodo aplicaci&oacute;n</td>
        </tr>
	<tr>
		<td colspan=2>
                    <input type="hidden" name="alergias" id="alergias" />
                    <button type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('frm_insert')">Guardar<i class="icon-hdd"></i></button>
                    <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/reglavacuna/'" class="btn btn-primary">Cancelar<i class="icon-arrow-left"></i></button>
                <td>
	</tr>
</table>
</div>
</form>