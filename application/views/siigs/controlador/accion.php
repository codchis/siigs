<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
<?php if (!empty($controlador_acciones) && !count($controlador_acciones) == 0) { ?>
<?php echo validation_errors(); ?>
<div class="table table-striped">
<?php echo form_open(DIR_SIIGS.'/controlador/accion/'. $id_controlador) ?>
<table>
<thead>
	<tr>
	<th>Acciones del controlador</th>
	</tr>
</thead>
<?php foreach ($controlador_acciones as $controlador_accion): ?>
	<tr>
		<td><input type="checkbox" name="acciones[]" value="<?php echo $controlador_accion->id; ?>" <?php if ($controlador_accion->activo) echo "checked"; ?>><?php echo $controlador_accion->accion; ?></td>
	</tr>
<?php endforeach ?>
<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $id_controlador;?>" />
		<input type="submit" name="submit" onclick="if (confirm('Esta acci&oacute;n podr&iacute;a afectar los permisos a los grupos, desea continuar?')) {return true ;} else {return false;}" value="Guardar" class="btn btn-primary"/>
		<td>
	</tr>
</table>
</form>
<?php } else {?>
<table>
<thead>
<tr>
	<th>No se encontraron registros</th>
</tr>
</thead>
</table>
<?php } ?>
</div>