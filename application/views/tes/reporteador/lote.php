<link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    
    <script type="text/javascript" src="/resources/js/validaciones.js"></script><script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

var g=new Date();
		var option = 
		{
			changeMonth: true,
			changeYear: true,
			duration:"fast",
			dateFormat: 'dd-mm-yy',
			constrainInput: true,
			firstDay: 1,
			closeText: 'X',
			showOn: 'both',
			buttonImage: '/resources/images/calendar.gif',
			buttonImageOnly: true,
			buttonText: 'Clic para seleccionar una fecha',
			yearRange: '1900:'+g.getFullYear(),
			showButtonPanel: false
		}
$(document).ready(function(){
	
	$("#desde").datepicker(optionsFecha);
	$("#hasta").datepicker(optionsFecha);
	
	$("a#fba1").fancybox({
		'width'         : '90%',
		'height'        : '90%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',	
		onComplete      : function(){
            $('#fancybox-frame').load(function(){
                $.fancybox.hideActivity();
            });
        }
	});
	$("a#fba1").click(function(e) {
        $.fancybox.showActivity();
    });
	
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
        echo $msgResult.'<br /><br />'; ?>
<fieldset style="width: 60%">
<?php echo form_open(DIR_TES.'/reporte_sincronizacion/lote/'); ?>
<table width="100%">
<tr>
<td>Codigo: </td>
<td colspan="3"><input type="text" name="lote" style="width:98%" placeholder="<?php if(isset( $_POST["lote"]))echo $_POST["lote"]; else echo "Codigo de barras";?>"  /></td>
</tr>
<tr>
<td width="25%">Jurisdicción: </td>
<td width="25%"> <?php  echo form_dropdown('juris', $juris); ?></td>
<td width="25%">Municipio: </td>
<td width="25%"> <?php  echo form_dropdown('municipios', $municipios); ?></td>
</tr>
<tr>
<td>Localidad: </td>
<td> <?php  echo form_dropdown('localidades', $localidades); ?></td>
<td>UM: </td>
<td> <?php  echo form_dropdown('ums', $ums); ?></td>
</tr>
<tr>
<td>Desde: </td>
<td><input name="desde" type="text" id="desde" placeholder="<?php if(isset( $_POST["desde"]))echo $_POST["desde"]; else echo "dd-mm-YYYY";?>" style="width:58%" maxlength="10" readonly="readonly"  /></td>
<td>Hasta: </td>
<td><input name="hasta" type="text" id="hasta" placeholder="<?php if(isset( $_POST["hasta"]))echo $_POST["hasta"]; else echo "dd-mm-YYYY";?>" style="width:58%" maxlength="10" readonly="readonly"  /></td>
</tr>
<tr>
<td colspan='4'>
<button type="submit" name="btnProcesar" id="btnProcesar"  class="btn btn-primary" >Buscar <i class="icon-search"></i></button></td>
</tr>
</table>
</form>
</fieldset>
<hr />
<div id="contenido_lote">
<?php for($i = 0; $i < $count; $i++){?>
<div id='popimpr<?php echo $i?>'  >
<table border="0" width="100%" id="tabla<?php echo $i?>" class="table table-striped  ">
<thead>
	<tr class="tr1">
		<th width="49%"><h2>Atributo</h2></th>
		<th width="36%"><h2>Valor</h2></th>
		<th width="15%"><h2>Listar</h2></th>
		
	</tr>
    </thead>
	<tr class="tr2">
		<td>Codigo</td>
		<td><?php echo $datos[$i]["lote"]; ?></td>
		<td>&nbsp;</td>
		
	</tr>
    
    <tr class="tr1">
		<td>Tipo</td>
		<td><?php echo $datos[$i]["tipo"]; ?></td>
		<td>&nbsp;</td>
		
	</tr>
    
    <tr class="tr2">
		<td>Cant. Aplicada</td>
		<td><?php echo $datos[$i]["cantidad"]; ?></td>
		<td><a href="/<?php echo DIR_TES?>/reporte_sincronizacion/lote_view/<?php echo $datos[$i]["lote"]; ?>/Personas a las que se les aplico <?php echo $datos[$i]["tipo"]; ?>/1" id="fba1"><img src="/resources/images/listar.png" style="border:none; width:30px; height:30px;" title="ver detalle" /></a></td>
		
	</tr>
    
    <tr class="tr1">
		<td>UM´s</td>
		<td><?php echo $datos[$i]["ums"]; ?></td>
		<td><a href="/<?php echo DIR_TES?>/reporte_sincronizacion/lote_view/<?php echo $datos[$i]["lote"]; ?>/Unidades medicas donde se aplico <?php echo $datos[$i]["tipo"]; ?>/2" id="fba1"><img src="/resources/images/listar.png" style="border:none; width:30px; height:30px;" title="ver detalle" /></a></td>
		
	</tr>
    
    <tr class="tr2">
		<td>Localidades</td>
		<td><?php echo $datos[$i]["localidades"] ?></td>
		<td><a href="/<?php echo DIR_TES?>/reporte_sincronizacion/lote_view/<?php echo $datos[$i]["lote"]; ?>/Localidades donde se aplico <?php echo $datos[$i]["tipo"]; ?>/3/<?php echo $datos[$i]["lugar"]; ?>" id="fba1"><img src="/resources/images/listar.png" style="border:none; width:30px; height:30px;" title="ver detalle" /></a></td>
		
	</tr>
    
    <tr class="tr1">
		<td>Personas que no fueron atendidas en su unidad medica</td>
		<td><?php echo $datos[$i]["personas"] ?></td>
		<td><a href="/<?php echo DIR_TES?>/reporte_sincronizacion/lote_view/<?php echo $datos[$i]["lote"]; ?>/Personas que no pertenecen <?php echo $datos[$i]["tipo"]; ?>/4/<?php echo $datos[$i]["lugar"]; ?>" id="fba1"><img src="/resources/images/listar.png" style="border:none; width:30px; height:30px;" title="ver detalle" /></a></td>
		
	</tr>
</table>
</div>
<div align="right">
<a id="csv<?php echo $i?>" href=""><img src="/resources/images/csv.png" style="border:none; "  title="Exportar a CSV" class="btn btn-primary"/></a>
<a id="pdf<?php echo $i?>" href=""><img src="/resources/images/pdf.png" style="border:none; "  title="Exportar a PDF" class="btn btn-primary"/></a>
<a id="exc<?php echo $i?>" href=""><img src="/resources/images/excel.png" style="border:none;" title="Exportar a EXCEL" class="btn btn-primary"/></a>
</div>
<hr /><hr />
<script>
$(document).ready(function(){
	$("#pdf<?php echo $i?>").click(function(e) {
        mandarimprimir(document,"popimpr<?php echo $i?>","");
		return false;
    });
	$("#csv<?php echo $i?>").click(function(e) {
		var data=$('#tabla<?php echo $i?>').table2CSV();
       	download(data, "tescsv.csv", "text/csv");
		return false;
    });
	$("#exc<?php echo $i?>").click(function(e) {
        var data=$('#popimpr<?php echo $i?>').html();
       	download(data, "tesexcel.xls", "application/vnd.ms-excel");
		return false;
    });
});
</script>
<?php } 
?>
</div>