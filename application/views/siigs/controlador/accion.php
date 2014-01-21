<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>

<script type="text/javascript">
$(document).ready(function(){
    $("a#detalles").fancybox({
        'width'         : '50%',
        'height'        : '60%',				
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic',
        'type'			: 'iframe',									
    }); 
});
</script>
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
    <th>Ayuda</th>
	</tr>
</thead>
<?php foreach ($controlador_acciones as $controlador_accion): ?>
	<tr>
		<td><input type="checkbox" name="acciones[]" value="<?php echo $controlador_accion->id; ?>" <?php if ($controlador_accion->activo) echo "checked"; ?>><?php echo $controlador_accion->accion; ?></td>
        <td><a id="detalles"  href="<?php echo site_url().DIR_SIIGS; ?>/controlador/help/<?php echo $controlador_accion->id;?>" class="btn btn-primary">Ayuda</a></td>
	</tr>
<?php endforeach ?>
<tr>
		<td colspan=2>
		<input type="hidden" name="id" value="<?php echo $id_controlador;?>" />
		<input type="submit" name="submit" onclick="if (confirm('Esta acci&oacute;n podr&iacute;a afectar los permisos a los grupos, desea continuar?')) {return true ;} else {return false;}" value="Guardar" class="btn btn-primary"/>
                <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/controlador'" class="btn btn-primary" />
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