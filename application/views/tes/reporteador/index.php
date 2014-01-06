<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';
$(document).ready(function(){
    $('select[name="juris"]').change(function(e){
    	$('select[name="municipios"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
    	;
    	$('select[name="localidades"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
        $.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/3/'+$('select[name="juris"]').val(),
            dataType: 'json'
        }).done(function(municipios){
            $.each(municipios, function(index) {
                option = $('<option />');
                option.val(municipios[index].id);
                option.text(municipios[index].descripcion);

                $('select[name="municipios"]').append(option);
            });
        });
    });
    
    $('select[name="municipios"]').change(function(e){
	   	$('select[name="localidades"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
		$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/4/'+$('select[name="municipios"]').val(),
            dataType: 'json'
        }).done(function(localidades){
            $.each(localidades, function(index) {
                option = $('<option />');
                option.val(localidades[index].id);
                option.text(localidades[index].descripcion);

                $('select[name="localidades"]').append(option);
            });
        });
    });
    
    $('select[name="localidades"]').change(function(e){
		$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/5/'+$('select[name="localidades"]').val(),
            dataType: 'json'
        }).done(function(ums){
            $.each(ums, function(index) {
                option = $('<option />');
                option.val(ums[index].id);
                option.text(ums[index].descripcion);

                $('select[name="ums"]').append(option);
            });
        });
    });
});
</script>
<?php 
$opcion_index = Menubuilder::isGranted(DIR_TES.'::reporteador::index');
$opcion_rpt1 = Menubuilder::isGranted(DIR_TES.'::reporteador::cobXtipo');
$opcion_rpt2 = Menubuilder::isGranted(DIR_TES.'::reporteador::concAct');
$opcion_rpt3 = Menubuilder::isGranted(DIR_TES.'::reporteador::segRv');
$reports = array(
		1  => 'Cobertura por Tipo de Biológico',
		2  => 'Concentrado de Actividades',
		3  => 'Seguimiento RV-1 y RV-5 a menores de 1 año',
);
$juris[''] = 'Seleccione una opción';
foreach($jurisdicciones as $row)
{
    $juris[$row->id] = $row->descripcion;
}
$municipios[''] = 'Seleccione una opción';
$localidades[''] = 'Seleccione una opción';
$ums[''] = 'Seleccione una opción';
if (!$opcion_rpt1) unset($reports[1]);
if (!$opcion_rpt2) unset($reports[2]);
if (!$opcion_rpt3) unset($reports[3]);
?>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>'; ?>
<fieldset style="width: 50%">
<?php echo form_open(DIR_TES.'/reporteador/index/'); ?>
<div class="table table-striped">
<table>
<tr>
<td>Reporte a generar:</td>
<td colspan='3'> <?php  if(isset($reports)) echo form_dropdown('reports', $reports); ?></td>
</tr>
<tr>
<td>Jurisdicción:</td>
<td> <?php  echo form_dropdown('juris', $juris); ?></td>
<td>Municipio:</td>
<td> <?php  echo form_dropdown('municipios', $municipios); ?></td>
</tr>
<tr>
<td>Localidad:</td>
<td> <?php  echo form_dropdown('localidades', $localidades); ?></td>
<td>UM:</td>
<td> <?php  echo form_dropdown('ums', $ums); ?></td>
</tr>
<tr>
<td colspan='4'>
<input type="submit" name="btnProcesar" id="btnProcesar" value="Procesar" class="btn btn-primary" /></td>
</tr>
</table>
</div>
</form>
</fieldset>