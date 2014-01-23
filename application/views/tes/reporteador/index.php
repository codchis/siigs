    <link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>	
<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';
function generaUrl(param, path){
    if ($('select[name="'+param+'"]').val() != ''){
		switch(param){
			case 'ums':
				path = path+"/5/"+$('select[name="'+param+'"]').val();
				break;
			case 'localidades':
				path = path+"/4/"+$('select[name="'+param+'"]').val();
				break;
			case 'municipios':
				path = path+"/3/"+$('select[name="'+param+'"]').val();
				break;
			case 'juris':
				path = path+"/2/"+$('select[name="'+param+'"]').val();
				break;
			case 'estados':
				path = path+"/1/"+$('select[name="'+param+'"]').val();
				break;
		}
		return path;
    }
	return '';
}

$(document).ready(function(){
	$("#fecha_corte").datepicker(optionsFecha);
	
    $("a").click(function(event){
    	if ($(this).attr("id").indexOf("rpt") != -1) { // solo valida clicks de anchors de reportes
    		document.getElementById("alert");
			document.getElementById("alert").className="warning";
			
	        // fecha obligatoria en 3 primeros reportes
	        if (($(this).attr("id") == 'rpt0' || $(this).attr("id") == 'rpt1' || $(this).attr("id") == 'rpt2') && $('#fecha_corte').val() == '')
	        {
	        	document.getElementById("alert").innerHTML='<div>Capture la fecha de corte</div>';
				event.preventDefault();
	        }
	        else // al menos un filtro de búsqueda debe estar seleccionado
	        {
	        	pathBase = $(this).attr("href");
		        var arrPath = $(this).attr("href").split('/');
		        if(arrPath.length != 6){
			        arrPath.splice(6);
			        arrPath.splice(7);
					pathBase = arrPath.join('/');
		        }
		        path = generaUrl("ums", pathBase);
		        if (path == ''){
		            path = generaUrl("localidades", pathBase);
		            if (path == ''){
		                path = generaUrl("municipios", pathBase);
		                if (path == ''){
		                    path = generaUrl("juris", pathBase);
		                    if (path == ''){
		                    	path = generaUrl("estados", pathBase);
		                    	if (path == ''){
		            				document.getElementById("alert").innerHTML='<div>No hay parámetros para realizar la búsqueda</div>';
			        				event.preventDefault();
			        				return;
		                    	}
		    				}
		    			}
		    		}
		    	}
		    	$(this).attr("href", path);
		    	// se agrega el parámetro fecha cuando aplique
		    	if ($(this).attr("id") == 'rpt0' || $(this).attr("id") == 'rpt1' || $(this).attr("id") == 'rpt2')
		    		$(this).attr("href", path + '/'+ $('#fecha_corte').val());
		        $.fancybox({
		    		'width'             : '90%',
		    		'height'            : '90%',				
		    		'transitionIn'	: 'elastic',
		    		'transitionOut'	: 'elastic',
		    		'type'			: 'iframe',	
		    		'href'			: this.href,							
		    	});
		        event.preventDefault();
		        document.getElementById("alert").className="";
		        document.getElementById("alert").innerHTML='';
	        }
    	}
    });
	
    $('select[name="estados"]').change(function(e){
    	$('select[name="juris"]')
        .find('option')
        .remove()
        .end()
        .append('<option value="">Seleccione una opcion</option>')
        .val('')
	;
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
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/2/'+$('select[name="estados"]').val(),
            dataType: 'json'
        }).done(function(juris){
            $.each(juris, function(index) {
                option = $('<option />');
                option.val(juris[index].id);
                option.text(juris[index].descripcion);

                $('select[name="juris"]').append(option);
            });
        });
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
$opcion_rpt4 = Menubuilder::isGranted(DIR_TES.'::reporteador::censoNom');
$opcion_rpt5 = Menubuilder::isGranted(DIR_TES.'::reporteador::esqIncomp');
$estad[''] = 'Seleccione una opción';
foreach($estados as $row)
{
    $estad[$row->id] = $row->descripcion;
}
$juris[''] = 'Seleccione una opción';
$municipios[''] = 'Seleccione una opción';
$localidades[''] = 'Seleccione una opción';
$ums[''] = 'Seleccione una opción';
if (!$opcion_rpt1) unset($datos[0]);
if (!$opcion_rpt2) unset($datos[1]);
if (!$opcion_rpt3) unset($datos[2]);
if (!$opcion_rpt4) unset($datos[3]);
if (!$opcion_rpt5) unset($datos[4]);
?>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>'; ?>
<fieldset style="width: 50%">
<?php echo form_open(DIR_TES.'/reporteador/index/'); ?>
<div id="alert"></div>
<div class="table table-striped">
<table>
<tr>
<td>Fecha corte:</td>
<td><input type="text" style="width:58%;" id="fecha_corte" name="fecha_corte" value="<?php echo set_value('fecha_corte', ''); ?>" /></td>
<td>Estado:</td>
<td> <?php  echo form_dropdown('estados', $estad); ?></td>
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
</table>
</div>
<div id='popimpr' class="table table-striped  " >
<table border="0" width="100%" id="tabla">
	<tr>
		<th><h2>Atributo</h2></th>
		<th><h2>Listar</h2></th>
		
	</tr>
	<?php 
	$cont = 0;
	if (isset($datos)) foreach ($datos as $dato): ?>
	<tr>
		<td><?php echo $dato["atributo"] ?></td>
		<td><a href="/<?php echo DIR_TES?>/reporteador/view/<?php echo $dato["lista"] ?>/<?php echo $dato["atributo"] ?>" id="rpt<?php echo $cont ?>"><img src="/resources/images/listar.png" style="border:none; width:30px; height:30px;" title="ver detalle" /></a></td>
		
	</tr>
	<?php $cont++; endforeach ?>
</table>
</div>
</form>
</fieldset>