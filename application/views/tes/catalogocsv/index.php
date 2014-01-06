<?php 
$opcion_view = Menubuilder::isGranted(DIR_TES.'::catalogocsv::view');
$opcion_update = Menubuilder::isGranted(DIR_TES.'::catalogocsv::update');
$opcion_create = Menubuilder::isGranted(DIR_TES.'::catalogocsv::createTablePob');
?>
<h2><?php echo $title; ?></h2>
<?php
if(!empty($msgResult))
echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
 ?>

<div class="table table-striped">
<table>
<thead>
	<tr>
	<th><h2>Nombre</h2></th>
	<?php if($opcion_view) { ?><th></th><?php } ?>
	<?php if($opcion_update) { ?><th></th><?php } ?>
	<?php if($opcion_create) { ?><th></th><?php } ?>
	</tr>
</thead>

<?php if ( !empty($catalogos) && !count($catalogos) == 0) { ?>
<?php foreach ($catalogos as $catalogo_item): ?>
	<tr>
		<td><?php if ($catalogo_item->nombre == CAT_POBLACION) $esPoblacion = 1; else $esPoblacion = 0; echo $catalogo_item->nombre ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_TES; ?>/catalogocsv/view/<?php echo $catalogo_item->nombre ?>" class="btn btn-primary">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES; ?>/catalogocsv/update/<?php echo $catalogo_item->nombre ?>" class="btn btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_create) { ?><td>
            <?php if ($esPoblacion == 1) { 
                echo '<a href="/'.DIR_TES.'/catalogocsv/createTablePob/" class="btn btn-primary">Crear tabla</a>';
            }
            if ($catalogo_item->nombre == CAT_GEOREFERENCIA) {
                echo '<a href="/'.DIR_TES.'/catalogocsv/createTableGeo/" class="btn btn-primary">Crear tabla</a>';
            } ?>
        </td>
        <?php } ?>
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
<?php } ?>
</table>
</div>