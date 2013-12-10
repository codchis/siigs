<?php 
$opcion_insert = Menubuilder::isGranted(DIR_TES.'::reglavacuna::insert');
$opcion_view = Menubuilder::isGranted(DIR_TES.'::reglavacuna::view');
$opcion_update = Menubuilder::isGranted(DIR_TES.'::reglavacuna::update');
$opcion_delete = Menubuilder::isGranted(DIR_TES.'::reglavacuna::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php if ( !empty($acciones) && !count($acciones) == 0) { ?>
<table>
<thead>
	<tr>
	<th>Nombre</th>
	<th>Descripci&oacute;n</th>
	<th>M&eacute;todo</th>
	<?php if($opcion_view) { ?><th>Detalles</th><?php } ?>
	<?php if($opcion_update) { ?><th>Modificar</th><?php } ?>
	<?php if($opcion_delete) { ?><th>Eliminar</th><?php } ?>
	</tr>
</thead>
<?php foreach ($acciones as $accion_item): ?>
	<tr>
		<td><?php echo $accion_item->nombre ?></td>
		<td><?php echo $accion_item->descripcion ?></td>
		<td><?php echo $accion_item->metodo ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/reglavacuna/view/<?php echo $accion_item->id ?>">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/reglavacuna/update/<?php echo $accion_item->id ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/reglavacuna/delete/<?php echo $accion_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta acci�n?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/reglavacuna/insert">Crear Nuevo</a><?php } ?>
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
	<td>
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/reglavacuna/insert">Crear Nuevo</a><?php } ?>
	</td>
</tr>
</table>
<?php } ?>