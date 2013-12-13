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
<?php if ( !empty($reglas) && !count($reglas) == 0) { ?>
<table>
<thead>
	<tr>
	<th>Vacuna</th>
	<th>Tipo Aplicación</th>
	<th>Desde</th>
        <th>Hasta</th>
        <th>Vacuna Previa</th>
	<?php if($opcion_view) { ?><th>Detalles</th><?php } ?>
	<?php if($opcion_update) { ?><th>Modificar</th><?php } ?>
	<?php if($opcion_delete) { ?><th>Eliminar</th><?php } ?>
	</tr>
</thead>
<?php foreach ($reglas as $regla_item): ?>
	<tr>
		<td><?php echo $regla_item->vacuna ?></td>
		<td><?php echo $regla_item->aplicacion ?></td>
		<td><?php echo $regla_item->desde ?></td>
                <td><?php echo $regla_item->hasta ?></td>
                <td><?php echo $regla_item->previa ?></td>
                    <?php if($opcion_view) { ?><td><a href="/<?php echo DIR_TES; ?>/reglavacuna/view/<?php echo $regla_item->id ?>">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES; ?>/reglavacuna/update/<?php echo $regla_item->id ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_TES; ?>/reglavacuna/delete/<?php echo $regla_item->id ?>" onclick="if (confirm('Realmente desea eliminar esta regla?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<tr>
	<td colspan=6 >
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES; ?>/reglavacuna/insert">Crear Nuevo</a><?php } ?>
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
	<?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES; ?>/reglavacuna/insert">Crear Nuevo</a><?php } ?>
	</td>
</tr>
</table>
<?php } ?>