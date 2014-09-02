    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    <link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>	

<script type="text/javascript">
$(document).ready(function(){ 
	$("a#tabletas").fancybox({
		'width'             : '50%',
		'height'            : '60%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',									
	}); 
    $("#fecha_inicio").datepicker(optionsFecha);
    $("#fecha_fin").datepicker(optionsFecha);
    obligatorios("notificacion");
});
</script>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_TES.'/notificacion/update/'.$notificacion_item->id,array('onkeyup' => 'limpiaformulario(\'notificacion\')', 'id' => 'notificacion', 'onclick' => 'limpiaformulario(\'notificacion\')')); ?>
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
	<div class="table table-striped">
	<table>
	<tr>
		<td><label for="titulo">Titulo</label></td>
		<td><input type="text" name="titulo" title='requiere' value="<?php echo $notificacion_item->titulo ?>" /></td>
	</tr>
	<tr>
		<td><label for="contenido">Nombre</label></td>
		<td><textarea rows="4" cols="50" title='requiere' name="contenido"><?php echo $notificacion_item->contenido ?></textarea></td>
	</tr>
	<tr>
		<td><label for="fecha_inicio">Fecha Inicio</label></td>
		<td><input type="text" id="fecha_inicio" title='requiere' name="fecha_inicio" value="<?php $time = strtotime(explode(' ', $notificacion_item->fecha_inicio)[0]);
			echo str_replace('/', '-', date('d/m/Y', $time)); ?>" /></td>
	</tr>
		<tr>
		<td><label for="fecha_fin">Fecha Fin</label></td>
		<td><input type="text" id="fecha_fin" title='requiere' name="fecha_fin" value="<?php $time = strtotime(explode(' ', $notificacion_item->fecha_fin)[0]);
			echo str_replace('/', '-', date('d/m/Y', $time)); ?>" /></td>
	</tr>
	<tr>
		<td><label for="id_arr_asu">Reportar a tabletas</label></td>
		<td><input type="text" name="id_arr_asuT" title='requiere' id="id_arr_asuT" readonly="true" value="<?php if(!empty($notification_item->tabletas)) echo implode(', ', $notificacion_item->tabletas) ?>" />
		<input type="hidden" name="id_arr_asu" id="id_arr_asu" readonly="true" value="<?php  if(!empty($notificacion_item->id_arr_asu)) echo $notificacion_item->id_arr_asu ?>" />
		<a href='/<?php echo DIR_TES?>/tree/create/TES/Tabletas a notificar/2/check/0/id_arr_asu/id_arr_asuT/1/1/<?php echo urlencode(json_encode(array(null)));?>/<?php echo urlencode(json_encode(array(1,2,3,4,5)));?>' id="tabletas" class="btn btn-small btn-primary btn-icon">Seleccionar</a>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="id" value="<?php echo $notificacion_item->id; ?>"/>
		<button type="submit" name="submit" id="guardar" class="btn btn-small btn-primary btn-icon" onclick="return validarFormulario('notificacion')" >Guardar <i class="icon-hdd"></i></button>
		<button type="button"  onclick="window.location.href='/<?php echo DIR_TES?>/notificacion/'" class="btn btn-small btn-primary btn-icon">Cancelar <i class="icon-arrow-left"></i></button>
		</td>
	</tr>
</table>
</div>
</form>