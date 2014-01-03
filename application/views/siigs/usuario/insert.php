<h2><?php echo $title ?></h2>
<?php
	if(!empty($msgResult))
        echo $msgResult.'<br /><br />';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/insert') ?>
<div class="table table-striped">
	<table>
	<tr>
		<td><label for="id_grupo">Grupo</label></td>
		<td><?php echo form_dropdown('id_grupo', $grupos, ($this->input->post('id_grupo')) ? $this->input->post('id_grupo') : '-1'); ?></td>
	</tr>
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="clave">Clave</label></td>
		<td><input type="password" name="clave" value="<?php echo set_value('clave', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="repiteclave">Repetir Clave</label></td>
		<td><input type="password" name="repiteclave" value="<?php echo set_value('repiteclave', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="apellido_paterno">Apellido Paterno</label></td>
		<td><input type="text" name="apellido_paterno" value="<?php echo set_value('apellido_paterno', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="apellido_materno">Apellido Materno</label></td>
		<td><input type="text" name="apellido_materno" value="<?php echo set_value('apellido_materno', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo electrónico</label></td>
		<td><input type="text" name="correo" value="<?php echo set_value('correo', ''); ?>" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/usuario/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>