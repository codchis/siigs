<script type="text/javascript">
$(document).ready(function(){
    $('#paginador a').click(function(e){
        e.preventDefault();
        pag = $(this).attr('href');
        $('#form_filter_bitacora').attr('action', pag);
        $('#form_filter_bitacora').submit();
    });

    $('#btnFiltrar').click(function(e){
        // Eliminar la pagina de la url del action
        action = $('#form_filter_bitacora').attr('action');
        action = action.replace(/\d+(\/)*$/,'');

        $('#form_filter_bitacora').attr('action',action);
        $('#form_filter_bitacora').submit();
    });
    
});
</script>
<?php 
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::usuario::insert');
$opcion_view = Menubuilder::isGranted(DIR_SIIGS.'::usuario::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::usuario::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::usuario::delete');
?>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>'; ?>
<fieldset style="width: 50%">
<?php echo form_open(DIR_SIIGS.'/usuario/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
Buscar usuario
<input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" /> 
<input type="submit" name="btnFiltrar" id="btnFiltrar" value="Buscar" class="btn btn-primary"/>
</form>
</fieldset>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS?>/usuario/insert" class="btn btn-primary">Crear nuevo</a><?php } ?>
<div class="table table-striped">
<table>
<thead>
	<tr>
		<th><h2>Nombre de usuario</h2></th>
		<th><h2>Nombre</h2></th>
		<th><h2>Apellido Paterno</h2></th>
		<th><h2>Apellido Materno</h2></th>
		<?php if($opcion_view) { ?><th><h2></h2></th> <?php } ?>
		<?php if($opcion_update) { ?><th><h2></h2></th> <?php } ?>
		<?php if($opcion_delete) { ?><th><h2></h2></th> <?php } ?>
	</tr>
</thead>
<tbody>
	<?php if (isset($users)) foreach ($users as $user_item): ?>
	<tr>
		<td><?php echo $user_item->nombre_usuario ?></td>
		<td><?php echo $user_item->nombre ?></td>
		<td><?php echo $user_item->apellido_paterno ?></td>
		<td><?php echo $user_item->apellido_materno ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_SIIGS?>/usuario/view/<?php echo $user_item->id ?>" class="btn btn-primary">Detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS?>/usuario/update/<?php echo $user_item->id ?>" class="btn btn-primary">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS?>/usuario/delete/<?php echo $user_item->id ?>"  class="btn btn-primary" onclick="if (confirm('Realmente desea eliminar este usuario?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
	</tr>
</tbody>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>
