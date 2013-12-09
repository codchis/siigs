<?php 
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php if ( !empty($catalogos) && !count($catalogos) == 0) { ?>
<table>
<thead>
	<tr>
	<th>Nombre</th>
	<?php if($opcion_view) { ?><th>Detalles</th><?php } ?>
	<?php if($opcion_update) { ?><th>Modificar</th><?php } ?>
	<?php if($opcion_delete) { ?><th>Eliminar</th><?php } ?>
	</tr>
</thead>
<?php foreach ($catalogos as $catalogo_item): ?>
	<tr>
		<td><?php echo $catalogo_item->nombre ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/view/<?php echo $catalogo_item->nombre ?>">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/update/<?php echo $catalogo_item->nombre ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/delete/<?php echo $catalogo_item->nombre ?>" onclick="if (confirm('Realmente desea eliminar este catálogo?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/catalogo/insert">Crear Nuevo</a><?php } ?>
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
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/catalogo/insert">Crear Nuevo</a><?php } ?>
	</td>
</tr>
</table>
<?php } ?>