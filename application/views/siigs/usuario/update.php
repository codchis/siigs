<script>
$(document).ready(function()
{
	obligatorios("usuario");
});
</script>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
	echo validation_errors(); 
	echo form_open(DIR_SIIGS.'/usuario/update/'.$user_item->id,array('onkeyup' => 'limpiaformulario(\'usuario\')', 'id' => 'usuario', 'onclick' => 'limpiaformulario(\'usuario\')')); ?>
    <div class="info requiere" style="width:93%">Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
	<div class="table table-striped">
	<table>
	<tr>
		<td><label for="id_grupo">Grupo</label></td>
		<td><?php echo form_dropdown('id_grupo', $grupos, $user_item->id_grupo, 'title=\'requiere\'') ?></td>
	</tr>
	<tr>
		<td><label for="nombre_usuario">Nombre de usuario</label></td>
		<td><?php echo $user_item->nombre_usuario ?></td>
	</tr>
	<tr>
		<td><label for="nombre">Nombre</label></td>
		<td><input type="text" name="nombre" title='requiere' value="<?php echo $user_item->nombre ?>" /></td>
	</tr>
	<tr>
		<td><label for="apellido_paterno">Apellido Paterno</label></td>
		<td><input type="text" name="apellido_paterno" title='requiere' value="<?php echo $user_item->apellido_paterno ?>" /></td>
	</tr>
		<tr>
		<td><label for="apellido_materno">Apellido Materno</label></td>
		<td><input type="text" name="apellido_materno" value="<?php echo $user_item->apellido_materno ?>" /></td>
	</tr>
	<tr>
		<td><label for="correo">Correo electrónico</label></td>
		<td><input type="text" name="correo" title='requiere' value="<?php echo $user_item->correo ?>" /></td>
	</tr>
	<tr>
		<td><label for="activo">Activo</label></td>
		<td><input type="checkbox" name="activo" <?php if ($user_item->activo == 1) echo 'checked' ?> /></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="hidden" name="id" value="<?php echo $user_item->id; ?>"/>
		<button type="submit" name="submit" id="guardar" class="btn btn-small btn-primary btn-icon" onclick="return validarFormulario('usuario')" >Guardar <i class="icon-hdd"></i></button>
		<button type="button"  onclick="window.location.href='/<?php echo DIR_SIIGS?>/usuario/'" class="btn btn-small btn-primary btn-icon">Cancelar <i class="icon-arrow-left"></i></button>
		</td>
	</tr>
</table>
</div>
</form>