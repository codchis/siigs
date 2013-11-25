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
		<th>Nombre</th>
		<th>Descripción</th>
		<th></th>
		<th></th>
		<th></th>
	<?php if (isset($groups)) foreach ($groups as $group_item): ?>
	<tr>
		<td><a href="/<?php echo DIR_SIIGS?>/grupo/<?php echo $group_item->id ?>/permiso">Permisos</a></td>
		<td><?php echo $group_item->nombre ?></td>
		<td><?php echo $group_item->descripcion ?></td>
		<td><a href="/<?php echo DIR_SIIGS?>/grupo/view/<?php echo $group_item->id ?>">Ver detalles</a></td>
		<td><a href="/<?php echo DIR_SIIGS?>/grupo/update/<?php echo $group_item->id ?>">Modificar</a></td>
		<td><a href="/<?php echo DIR_SIIGS?>/grupo/delete/<?php echo $group_item->id ?>" onclick="if (confirm('Realmente desea eliminar este grupo?')) { return true; } else {return false;}">Eliminar</a></td>
			</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
<a href="/<?php echo DIR_SIIGS?>/grupo/insert">Crear nuevo</a>
