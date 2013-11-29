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
$opcion_insert = Menubuilder::isGranted(DIR_SIIGS.'::grupo::insert');
$permiso_index = Menubuilder::isGranted(DIR_SIIGS.'::permiso::index');
$opcion_index = Menubuilder::isGranted(DIR_SIIGS.'::grupo::view');
$opcion_update = Menubuilder::isGranted(DIR_SIIGS.'::grupo::update');
$opcion_delete = Menubuilder::isGranted(DIR_SIIGS.'::grupo::delete');
?>
<h2><?php echo $title ?></h2>
<?php if(!empty($msgResult))
        echo $msgResult.'<br /><br />'; ?>
<fieldset style="width: 50%">
    <legend>Opciones de búsqueda</legend>
<?php echo form_open(DIR_SIIGS.'/grupo/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
Buscar grupo
<input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" /> 
<input type="submit" name="btnFiltrar" id="btnFiltrar" value="Buscar" />
</form>
</fieldset>
<br />
<table border="1">
		<?php if($permiso_index) { ?><th>&nbsp;</th><?php } ?>
		<th>Nombre</th>
		<th>Descripción</th>
		<?php if($opcion_index) { ?><th></th> <?php } ?>
		<?php if($opcion_update) { ?><th></th> <?php } ?>
		<?php if($opcion_delete) { ?><th></th> <?php } ?>
	<?php if (isset($groups)) foreach ($groups as $group_item): ?>
	<tr>
		<?php if($permiso_index) { ?><td><a href="/<?php echo DIR_SIIGS?>/grupo/<?php echo $group_item->id ?>/permiso">Permisos</a></td><?php } ?>
		<td><?php echo $group_item->nombre ?></td>
		<td><?php echo $group_item->descripcion ?></td>
		<?php if($opcion_index) { ?><td><a href="/<?php echo DIR_SIIGS?>/grupo/view/<?php echo $group_item->id ?>">Ver detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_SIIGS?>/grupo/update/<?php echo $group_item->id ?>">Modificar</a></td><?php } ?>
		<?php if($opcion_delete) { ?><td><a href="/<?php echo DIR_SIIGS?>/grupo/delete/<?php echo $group_item->id ?>" onclick="if (confirm('Realmente desea eliminar este grupo?')) { return true; } else {return false;}">Eliminar</a></td><?php } ?>
			</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_SIIGS?>/grupo/insert">Crear nuevo</a><?php } ?>
