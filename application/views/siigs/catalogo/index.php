<?php 
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::catalogo::delete');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>
     <?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS; ?>/catalogo/insert" class="btn btn-primary">Crear Nuevo<i class="icon-plus"></i></a><br/><br/><?php } ?>
<div class="table table-striped">
<table>
<thead>
	<tr>
	<th>Nombre</th>
        <th>Comentario</th>
	<?php if($opcion_view) { ?><th></th><?php } ?>
	<?php if($opcion_update) { ?><th></th><?php } ?>
	<?php if($opcion_delete) { ?><th></th><?php } ?>
	</tr>
</thead>
<?php if ( !empty($catalogos) && !count($catalogos) == 0) { ?>
<?php foreach ($catalogos as $catalogo_item): ?>
	<tr>
		<td><?php echo $catalogo_item->nombre ?></td>
                <td><?php echo $catalogo_item->comentario ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/view/<?php echo $catalogo_item->nombre ?>" class="btn btn-small btn-primary">Detalles<i class="icon-eye-open"></i></a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/update/<?php echo $catalogo_item->nombre ?>" class="btn btn-small btn-primary">Modificar<i class="icon-pencil"></i></a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogo/delete/<?php echo $catalogo_item->nombre ?>" class="btn btn-small btn-primary" onclick="if (confirm('Realmente desea eliminar este catálogo?')) { return true; } else {return false;}">Eliminar<i class="icon-remove"></i></a></td><?php } ?>
	</tr>
<?php endforeach ?>

<?php } else {?>
<thead>
<tr>
	<th colspan="5">No se encontraron registros</th>
</tr>
</thead>
<?php } ?>
</table>
</div>