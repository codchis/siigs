<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	if (count($notificacion_item) == 0) echo 'Registro no encontrado.<br><br>'; else {
?>
<div class="table table-striped">
<table>
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
		<td><?php $time = strtotime($notificacion_item->fecha_inicio);
			echo date('d/m/Y', $time);
			 ?></td>
	</tr>
		<tr>
		<td><label for="fecha_fin">Fecha Fin</label></td>
		<td><?php $time = strtotime($notificacion_item->fecha_fin);
			echo date('d/m/Y', $time);
			 ?></td>
	</tr>
	<tr>
		<td><label for="id_arr_asu">Tabletas</label></td>
		<td><?php foreach($notificacion_item->tabletas as $tableta){
			echo $tableta.'<br>';
		} ?></td>
	</tr>
</table>
</div>
<?php } ?>
<a href="<?php echo site_url().DIR_TES; ?>/notificacion/" class="btn btn-primary">Regresar al listado</a>