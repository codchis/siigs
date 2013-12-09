<?php 
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::raiz::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::raiz::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::raiz::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::raiz::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo $msgResult.'<br /><br />';
 ?>
<?php if ( !empty($raices) && !count($raices) == 0) { ?>
<table>
<thead>
	<tr>
	<th>Descripci&oacute;n</th>
	<?php if($opcion_view) { ?><th>Detalles</th><?php } ?>
	<?php if($opcion_update) { ?><th>Modificar</th><?php } ?>
	<?php if($opcion_delete) { ?><th>Eliminar</th><?php } ?>
	</tr>
</thead>
<?php foreach ($raices as $raiz_item): ?>
	<tr>
		<td><?php echo $raiz_item->descripcion ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/raiz/view/<?php echo $raiz_item->id ?>">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/raiz/update/<?php echo $raiz_item->id ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/raiz/delete/<?php echo $raiz_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta raiz?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/raiz/insert">Crear Nuevo</a><?php } ?>
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
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/raiz/insert">Crear Nuevo</a><?php } ?>
	</td>
</tr>
</table>
<?php } ?>