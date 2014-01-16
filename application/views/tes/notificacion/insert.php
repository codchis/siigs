    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
    <link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>	
<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
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
<?php
	if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_TES.'/notificacion/insert',array('onkeyup' => 'limpiaformulario(this.id)', 'id' => 'notificacion', 'onclick' => 'limpiaformulario(this.id)')); ?>
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
	<div class="table table-striped">
<table>
	<tr>
		<td><label for="titulo">Titulo</label></td>
		<td><input type="text" title='requiere' name="titulo" value="<?php echo set_value('titulo', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="contenido">Contenido</label></td>
		<td><textarea rows="4" cols="50" title='requiere' name="contenido"><?php echo set_value('contenido', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="fecha_inicio">Desde</label></td>
		<td><input type="text" id="fecha_inicio" title='requiere' name="fecha_inicio" value="<?php echo set_value('fecha_inicio', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="fecha_fin">Hasta</label></td>
		<td><input type="text" id="fecha_fin" title='requiere' name="fecha_fin" value="<?php echo set_value('fecha_fin', ''); ?>" /></td>
	</tr>
		<tr>
		<td><label for="id_arr_asu">Reportar a tabletas</label></td>
		<td><input type="text" name="id_arr_asuT" title='requiere' id="id_arr_asuT" readonly="true" value="<?php if(isset($_POST['id_arr_asuT'])) echo $_POST['id_arr_asuT']; ?>" />
		<input type="hidden" name="id_arr_asu" id="id_arr_asu" readonly="true" value="<?php echo set_value('id_arr_asu', ''); ?>" />
		<a href='/<?php echo DIR_TES?>/tree/create/TES/Tabletas a notificar/2/check/0/id_arr_asu/id_arr_asuT/1/1/<?php echo urlencode(json_encode(array(null)));?>' id="tabletas" class="btn btn-primary">Seleccionar</a>
		</td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('notificacion')" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_TES; ?>/notificacion/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>