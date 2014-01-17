<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	if (count($user_item) == 0) echo 'Registro no encontrado.<br><br>'; else {
?>
<div class="table table-striped">
<table>
	<tr>
		<td><label for="id">Id</label></td>
		<td><?php echo $user_item->id ?></td>
	</tr>
	<tr>
		<td><label for="id_grupo">Grupo</label></td>
		<td><?php echo $user_item->Grupo ?></td>
	</tr>
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><?php echo $user_item->nombre_usuario ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><?php echo $user_item->nombre ?></td>
	</tr>
	<tr>
		<td><label for="apellido_paterno">Apellido Paterno</label></td>
		<td><?php echo $user_item->apellido_paterno ?></td>
	</tr>
		<tr>
		<td><label for="apellido_materno">Apellido Materno</label></td>
		<td><?php echo $user_item->apellido_materno ?></td>
	</tr>
	<tr>
		<td><label for="correo">Correo electrónico</label></td>
		<td><?php echo $user_item->correo ?></td>
	</tr>
	<tr>
		<td><label for="activo">Activo</label></td>
		<td><input type="checkbox" disabled="true" name="activo" <?php if ($user_item->activo == 1) echo 'checked' ?> /></td>
	</tr>
</table>
</div>
<?php } ?>