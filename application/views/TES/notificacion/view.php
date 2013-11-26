<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	if (isset($notificacion_item)) {
?>
<table border="1">
	<tr>
		<td><label for="id">Id</label></td>
		<td><?php echo $notificacion_item->id ?></td>
	</tr>
	<tr>
		<td><label for="titulo">Titulo</label></td>
		<td><?php echo $notificacion_item->titulo ?></td>
	</tr>
	<tr>
		<td><label for="contenido">Contenido</label></td>
		<td><?php echo $notificacion_item->contenido ?></td>
	</tr>
	<tr>
		<td><label for="fecha_inicio">Fecha Inicio</label></td>
		<td><?php echo $notificacion_item->fecha_inicio ?></td>
	</tr>
		<tr>
		<td><label for="fecha_fin">Fecha Fin</label></td>
		<td><?php echo $notificacion_item->fecha_fin ?></td>
	</tr>
	<tr>
		<td><label for="id_arr_asu">Tabletas</label></td>
		<td><?php foreach($notificacion_item->tabletas as $tableta){
			echo $tableta.'<br>';
		} ?></td>
	</tr>
</table>
<?php } ?>