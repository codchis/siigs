<script type="text/javascript">
$(document).ready(function(){
    $('#tabletas').click(function(e){
    	alert('popup');
        e.preventDefault();
        $('#id_arr_asu').val('1,2,3');
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
		<td><input type="text" name="id_arr_asu" id="id_arr_asu" readonly="true" value="<?php echo set_value('id_arr_asu', ''); ?>" />
		<input type="submit" name="tabletas" id="tabletas" value="Seleccionar" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" /><td>
	</tr>
</table>
</form>