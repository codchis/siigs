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
echo $msgResult.'<br /><br />';
 ?>
<?php if ( !empty($entornos) && !count($entornos) == 0) { ?>
<table>
<thead>
	<tr>
	<th>Nombre</th>
	<th>Descripci&oacute;n</th>
	<th>Direcci&oacute;n IP</th>
	<th>Hostname</th>
	<th>Directorio</th>
	<?php if($opcion_view) { ?><th>Detalles</th><?php } ?>
	<?php if($controlador_index) { ?><th>Controladores</th><?php } ?>
	<?php if($opcion_update) { ?><th>Modificar</th><?php } ?>
	<?php if($opcion_delete) { ?><th>Eliminar</th><?php } ?>
	</tr>
</thead>
<?php foreach ($entornos as $entorno_item): ?>
	<tr>
		<td><?php echo $entorno_item->nombre ?></td>
		<td><?php echo $entorno_item->descripcion ?></td>
		<td><?php echo $entorno_item->ip ?></td>
		<td><?php echo $entorno_item->hostname ?></td>
		<td><?php echo $entorno_item->directorio ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/entorno/view/<?php echo $entorno_item->id ?>">Ver detalles</a></td><?php } ?>
		<?php if($controlador_index) { ?><td>
		<form id="frm<?php echo $entorno_item->id;?>" action="/<?php echo DIR_SIIGS; ?>/controlador" method="post">
		<input type="hidden" name="id_entorno" value="<?php echo $entorno_item->id;?>" />
		<a href="#" onclick="$('#frm<?php echo $entorno_item->id;?>').submit(); return false;">Ver controladores</a>
		</form>
		</td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/entorno/update/<?php echo $entorno_item->id ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/entorno/delete/<?php echo $entorno_item->id ?>" onclick="if (confirm('Realmente desea eliminar este entorno?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=9 >
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/entorno/insert">Crear Nuevo</a><?php } ?>
	</td>
</tr>
</table>
<?php } else {?>
<table>
<thead>
<tr>
	<th>No se encontraron registros</th>
</tr>
</thead>
<tr>
	<td >
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/entorno/insert">Crear Nuevo</a><?php } ?>
	</td>
</tr>
</table>
<?php } ?>