<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/update/'.$user_item->id) ?>
<div class="table table-striped">
	<table>
	<tr>
		<td><label for="id_grupo">Grupo</label></td>
		<td><?php echo form_dropdown('id_grupo', $grupos, $user_item->id_grupo) ?></td>
	</tr>
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><?php echo $user_item->nombre_usuario ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo $user_item->nombre ?>" /></td>
	</tr>
	<tr>
		<td><label for="apellido_paterno">Apellido Paterno</label></td>
		<td><input type="text" name="apellido_paterno" value="<?php echo $user_item->apellido_paterno ?>" /></td>
	</tr>
		<tr>
		<td><label for="apellido_materno">Apellido Materno</label></td>
		<td><input type="text" name="apellido_materno" value="<?php echo $user_item->apellido_materno ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo electrónico</label></td>
		<td><input type="text" name="correo" value="<?php echo $user_item->correo ?>" /></td>
	</tr>
	<tr>
		<td><label for="activo">Activo</label></td>
		<td><input type="checkbox" name="activo" <?php if ($user_item->activo == 1) echo 'checked' ?> /></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="id" value="<?php echo $user_item->id; ?>"/>
		<input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/usuario/'" class="btn btn-primary" />
		<td>
	</tr>
</table>
</div>
</form>