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
	<th>Detalles</th>
	<th>Modificar</th>
	<th>Eliminar</th>
	</tr>
</thead>
<?php foreach ($catalogos as $catalogo_item): ?>
	<tr>
		<td><?php echo $catalogo_item->nombre ?></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/view/<?php echo $catalogo_item->nombre ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/update/<?php echo $catalogo_item->nombre ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/delete/<?php echo $catalogo_item->nombre ?>" onclick="if (confirm('Realmente desea eliminar este catálogo?')) { return true; } else {return false;}">Eliminar</a></td>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<a href="/<?php echo DIR_SIIGS; ?>/catalogo/insert">Crear Nuevo</a>
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
	<a href="/<?php echo DIR_SIIGS; ?>/catalogo/insert">Crear Nuevo</a>
	</td>
</tr>
</table>
<?php } ?>