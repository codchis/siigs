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
	<th>Detalles</th>
	<th>Modificar</th>
	<th>Eliminar</th>
	</tr>
</thead>
<?php foreach ($raices as $raiz_item): ?>
	<tr>
		<td><?php echo $raiz_item->descripcion ?></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/raiz/view/<?php echo $raiz_item->id ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/raiz/update/<?php echo $raiz_item->id ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_SIIGS; ?>/raiz/delete/<?php echo $raiz_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta raiz?')) { return true; } else {return false;}">Eliminar</a></td>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<a href="/<?php echo DIR_SIIGS; ?>/raiz/insert">Crear Nuevo</a>
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
	<a href="/<?php echo DIR_SIIGS; ?>/raiz/insert">Crear Nuevo</a>
	</td>
</tr>
</table>
<?php } ?>