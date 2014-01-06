<?php 
$opcion_insert = Menubuilder::isGranted(DIR_TES.'::reglavacuna::insert');
$opcion_view = Menubuilder::isGranted(DIR_TES.'::reglavacuna::view');
$opcion_update = Menubuilder::isGranted(DIR_TES.'::reglavacuna::update');
$opcion_delete = Menubuilder::isGranted(DIR_TES.'::reglavacuna::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
 <?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES; ?>/reglavacuna/insert" class="btn btn-primary">Crear Nuevo</a><?php } ?>

<div class="table table-striped">
<table>
<thead>
	<tr>
	<th><h2>Vacuna</h2></th>
	<th><h2>Tipo Aplicación</h2></th>
	<th><h2>Desde</h2></th>
        <th><h2>Hasta</h2></th>
        <th><h2>Vacuna Previa</h2></th>
	<?php if($opcion_view) { ?><th></th><?php } ?>
	<?php if($opcion_update) { ?><th></th><?php } ?>
	<?php if($opcion_delete) { ?><th></th><?php } ?>
	</tr>
</thead>
<?php if ( !empty($reglas) && !count($reglas) == 0) { ?>
<?php foreach ($reglas as $regla_item): ?>
	<tr>
		<td><?php echo $regla_item->vacuna ?></td>
		<td><?php echo $regla_item->aplicacion ?></td>
		<td><?php echo $regla_item->desde ?></td>
                <td><?php echo $regla_item->hasta ?></td>
                <td><?php echo $regla_item->previa ?></td>
                    <?php if($opcion_view) { ?><td><a href="/<?php echo DIR_TES; ?>/reglavacuna/view/<?php echo $regla_item->id ?>" class="btn btn-primary">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES; ?>/reglavacuna/update/<?php echo $regla_item->id ?>" class="btn btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_TES; ?>/reglavacuna/delete/<?php echo $regla_item->id ?>" class="btn btn-primary" onclick="if (confirm('Realmente desea eliminar esta regla?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
<?php endforeach ?>
<?php } else {?>
<thead>
<tr>
	<th>No se encontraron registros</th>
</tr>
</thead>
<?php } ?>
</table>
</div>