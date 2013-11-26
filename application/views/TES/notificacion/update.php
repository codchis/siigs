<script type="text/javascript">
$(document).ready(function(){
    $('#tabletas').click(function(e){
    	alert('popup');
        e.preventDefault();
        $('#id_arr_asu').val('4,5');
    });   
});
</script>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_TES.'/notificacion/update/'.$notificacion_item->id) ?>
<table border="1">
	<tr>
		<td><label for="titulo">Titulo</label></td>
		<td><input type="text" name="titulo" value="<?php echo $notificacion_item->titulo ?>" /></td>
	</tr>
	<tr>
		<td><label for="contenido">Nombre</label></td>
		<td><textarea rows="4" cols="50" name="contenido"><?php echo $notificacion_item->contenido ?></textarea></td>
	</tr>
	<tr>
		<td><label for="fecha_inicio">Fecha Inicio</label></td>
		<td><input type="date" name="fecha_inicio" value="<?php echo explode(' ', $notificacion_item->fecha_inicio)[0] ?>" /></td>
	</tr>
		<tr>
		<td><label for="fecha_fin">Fecha Fin</label></td>
		<td><input type="date" name="fecha_fin" value="<?php echo explode(' ', $notificacion_item->fecha_fin)[0] ?>" /></td>
	</tr>
	<tr>
		<td><label for="id_arr_asu">Reportar a tabletas</label></td>
		<td><input type="text" name="id_arr_asu" id="id_arr_asu" readonly="true" value="<?php echo $notificacion_item->id_arr_asu ?>" />
		<input type="submit" name="tabletas" id="tabletas" value="Seleccionar" /></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="id" value="<?php echo $notificacion_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" />
		<td>
	</tr>
</table>
</form>