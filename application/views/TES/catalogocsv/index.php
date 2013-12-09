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
	</tr>
</thead>
<?php foreach ($catalogos as $catalogo_item): ?>
	<tr>
		<td><?php echo $catalogo_item->nombre ?></td>
		<td><a href="/<?php echo DIR_TES; ?>/catalogocsv/view/<?php echo $catalogo_item->nombre ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_TES; ?>/catalogocsv/update/<?php echo $catalogo_item->nombre ?>">Modificar</a></td>
	</tr>
<?php endforeach ?>

</table>
<?php } else {?>
<table>
<thead>
<tr>
	<th>No se encontraron registros</th>
</tr>
</thead>

</table>
<?php } ?>