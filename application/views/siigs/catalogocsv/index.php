<?php 
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::catalogocsv::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::catalogocsv::update');
$opcion_create = Menubuilder::isGranted(DIR_SIIGS.'::catalogocsv::createTablePob');
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
        <th><h2>Comentario</h2></th>
	<?php if($opcion_view) { ?><th></th><?php } ?>
	<?php if($opcion_update) { ?><th></th><?php } ?>
	<?php if($opcion_create) { ?><th></th><?php } ?>
        
	</tr>
</thead>

<?php if ( !empty($catalogos) && !count($catalogos) == 0) { ?>
<?php foreach ($catalogos as $catalogo_item): ?>
	<tr>
		<td><?php if ($catalogo_item->nombre == CAT_POBLACION) $esPoblacion = 1; else $esPoblacion = 0; echo $catalogo_item->nombre ?></td>
                <td><?php echo $catalogo_item->comentario ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogocsv/view/<?php echo $catalogo_item->nombre ?>" class="btn btn-small btn-primary btn-icon">Detalles<i class="icon-eye-open"></i></a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS; ?>/catalogocsv/update/<?php echo $catalogo_item->nombre ?>" class="btn btn-small btn-primary btn-icon">Modificar<i class="icon-pencil"></i></a></td><?php } ?>
		<td><?php if($opcion_create) { ?>
            <?php if ($esPoblacion == 1) { 
                echo '<a href="/'.DIR_SIIGS.'/catalogocsv/createTablePob/" class="btn btn-primary btn-small btn-icon">Crear&nbsp;Tabla<i class="icon-list-alt"></i></a>';
            }
            if ($catalogo_item->nombre == CAT_GEOREFERENCIA) {
                echo '<a href="/'.DIR_SIIGS.'/catalogocsv/createTableGeo/" class="btn btn-small btn-primary btn-icon">Crear&nbsp;Tabla<i class="icon-list-alt"></i></a>';
            }
            if ($catalogo_item->nombre == CAT_AGEB) {
                echo '<a href="/'.DIR_SIIGS.'/catalogocsv/createTableAgeb/" class="btn btn-small btn-primary btn-icon">Crear&nbsp;Tabla<i class="icon-list-alt"></i></a>';
            }
            ?>
                <?php } ?>
                </td>
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