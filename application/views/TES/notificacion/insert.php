    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
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
});
</script>
<h2><?php echo $title ?></h2>
<?php
	if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_TES.'/notificacion/insert') ?>
<table border="1">
	<tr>
		<td><label for="titulo">Titulo</label></td>
		<td><input type="text" name="titulo" value="<?php echo set_value('titulo', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="contenido">Contenido</label></td>
		<td><textarea rows="4" cols="50" name="contenido"><?php echo set_value('contenido', ''); ?></textarea></td>
	</tr>
	<tr>
		<td><label for="fecha_inicio">Desde</label></td>
		<td><input type="date" name="fecha_inicio" value="<?php echo set_value('fecha_inicio', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="fecha_fin">Hasta</label></td>
		<td><input type="date" name="fecha_fin" value="<?php echo set_value('fecha_fin', ''); ?>" /></td>
	</tr>
		<tr>
		<td><label for="id_arr_asu">Reportar a tabletas</label></td>
		<td><input type="text" name="id_arr_asuT" id="id_arr_asuT" readonly="true" value="<?php echo set_value('id_arr_asuT', ''); ?>" />
		<input type="hidden" name="id_arr_asu" id="id_arr_asu" readonly="true" value="<?php echo set_value('id_arr_asu', ''); ?>" />
		<a href='/<?php echo DIR_TES?>/Tree/tree/TES/Tabletas a notificar/2/check/0/id_arr_asu/id_arr_asuT/1/1/<?php echo urlencode(json_encode(array(null)));?>/<?php echo urlencode(json_encode(array(null)));?>' id="tabletas" class="cat">Seleccionar</a>
		</td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" /><td>
	</tr>
</table>
</form>