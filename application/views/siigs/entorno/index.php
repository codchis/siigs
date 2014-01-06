<?php 
$controlador_index = Menubuilder::isGranted(DIR_SIIGS.'::controlador::index');
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::entorno::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::entorno::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::entorno::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::entorno::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/entorno/insert" class="btn btn-primary" >Crear Nuevo</a><?php } ?>

<div class="table table-striped">
<table>
<thead>
	<tr>
	<th><h2>Nombre</h2></th>
	<th><h2>Descripci&oacute;n</h2></th>
	<th><h2>Direcci&oacute;n IP</h2></th>
	<th><h2>Hostname</h2></th>
	<th><h2>Directorio</h2></th>
	<?php if($opcion_view) { ?><th><h2></h2></th><?php } ?>
	<?php if($controlador_index) { ?><th><h2></h2></th><?php } ?>
	<?php if($opcion_update) { ?><th><h2></h2></th><?php } ?>
	<?php if($opcion_delete) { ?><th><h2></h2></th><?php } ?>
	</tr>
</thead>
<?php if ( !empty($entornos) && !count($entornos) == 0) { ?>
<?php foreach ($entornos as $entorno_item): ?>
	<tr>
		<td><?php echo $entorno_item->nombre ?></td>
		<td><?php echo $entorno_item->descripcion ?></td>
		<td><?php echo $entorno_item->ip ?></td>
		<td><?php echo $entorno_item->hostname ?></td>
		<td><?php echo $entorno_item->directorio ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/entorno/view/<?php echo $entorno_item->id ?>" class="btn btn-primary">Ver detalles</a></td><?php } ?>
		<?php if($controlador_index) { ?><td>
		<form id="frm<?php echo $entorno_item->id;?>" action="/<?php echo DIR_SIIGS; ?>/controlador" method="post">
		<input type="hidden" name="id_entorno" value="<?php echo $entorno_item->id;?>" />
		<a href="#" onclick="$('#frm<?php echo $entorno_item->id;?>').submit(); return false;" class="btn btn-primary">Ver controladores</a>
		</form>
		</td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/entorno/update/<?php echo $entorno_item->id ?>" class="btn btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/entorno/delete/<?php echo $entorno_item->id ?>"  class="btn btn-primary"onclick="if (confirm('Realmente desea eliminar este entorno?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>

<?php } else {?>

<thead>
<tr>
	<th colspan=9>No se encontraron registros</th>
</tr>
</thead>

<?php } ?>
</table>
</div>