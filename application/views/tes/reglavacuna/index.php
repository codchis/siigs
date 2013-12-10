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
	<th>Detalles</th>
	<th>Modificar</th>
	<th>Eliminar</th>
	</tr>
</thead>
<?php foreach ($acciones as $accion_item): ?>
	<tr>
		<td><?php echo $accion_item->nombre ?></td>
		<td><?php echo $accion_item->descripcion ?></td>
		<td><?php echo $accion_item->metodo ?></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/accion/view/<?php echo $accion_item->id ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/accion/update/<?php echo $accion_item->id ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/accion/delete/<?php echo $accion_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta acción?')) { return true; } else {return false;}">Eliminar</a></td>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<a href="/<?php echo DIR_SIIGS; ?>/accion/insert">Crear Nuevo</a>
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
	<a href="/<?php echo DIR_SIIGS; ?>/accion/insert">Crear Nuevo</a>
	</td>
</tr>
</table>
<?php } ?>