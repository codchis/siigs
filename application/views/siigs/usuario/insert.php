<script>
$(document).ready(function()
{
	obligatorios("usuario");
});
</script>
<h2><?php echo $title ?></h2>
<?php
	if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/insert',array('onkeyup' => 'limpiaformulario(this.id)', 'id' => 'usuario', 'onclick' => 'limpiaformulario(this.id)')); ?>
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
	<div class="table table-striped">
	<table>
	<tr>
		<td><label for="id_grupo">Grupo</label></td>
		<td><?php echo form_dropdown('id_grupo', $grupos, '', 'title=\'requiere\''); ?></td>
	</tr>
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><input type="text" name="nombre_usuario" title='requiere' value="<?php echo set_value('nombre_usuario', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="clave">Clave</label></td>
		<td><input type="password" name="clave" title='requiere' value="<?php echo set_value('clave', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="repiteclave">Repetir Clave</label></td>
		<td><input type="password" name="repiteclave" title='requiere' value="<?php echo set_value('repiteclave', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' value="<?php echo set_value('nombre', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="apellido_paterno">Apellido Paterno</label></td>
		<td><input type="text" name="apellido_paterno" title='requiere' value="<?php echo set_value('apellido_paterno', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="apellido_materno">Apellido Materno</label></td>
		<td><input type="text" name="apellido_materno" value="<?php echo set_value('apellido_materno', ''); ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo electrónico</label></td>
		<td><input type="text" name="correo" title='requiere' value="<?php echo set_value('correo', ''); ?>" /></td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('usuario')" />
		<input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/usuario/'" class="btn btn-primary" /><td>
	</tr>
</table>
</div>
</form>