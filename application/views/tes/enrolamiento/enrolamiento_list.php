<link href="/resources/css/alert.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript">
$(document).ready(function()
{
	<?php if(!empty($id)){?>
	if(confirm("Registro agregado exitosamente\n\n ¿Deseas guardarlo en la tarjeta?"))
	{
		$("#secretIFrame").attr("src","/<?php echo DIR_TES?>/enrolamiento/file_to_card/<?php echo $id?>");
	}
	else window.location.href = "/<?php echo DIR_TES?>/enrolamiento/";
	<?php } ?>
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
$opcion_insert = Menubuilder::isGranted(DIR_TES.'::enrolamiento::insert');
$opcion_view = Menubuilder::isGranted(DIR_TES.'::enrolamiento::view');
$opcion_update = Menubuilder::isGranted(DIR_TES.'::enrolamiento::update');
$opcion_print = Menubuilder::isGranted(DIR_TES.'::enrolamiento::print');
?>
<?php 
if(!empty($msgResult))
	echo "<div class='".$this->session->flashdata('infoclass')."'>".$msgResult."</div>";
?>
<div class="input-append"><h2><?php echo $title ?></h2>

<?php echo form_open(DIR_TES.'/enrolamiento/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
Buscar usuario
<input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" class="spa10" placeholder="Buscar Paciente" /> 
<input type="submit" name="btnFiltrar" id="btnFiltrar" value="Buscar" class="btn btn-primary"/>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES?>/enrolamiento/insert" class="btn btn-primary" style="margin-left:5px">Crear nuevo</a><?php } ?>
</form>
</div>
<div class="table table-striped  " >
<table width="100%">
<thead>
	<tr>
		<th><h2>CURP</h2></th>
		<th><h2>Nombre</h2></th>
		<th><h2>Ap. Paterno</h2></th>
		<th><h2>Ap. Materno</h2></th>
		<?php if($opcion_view) { ?><th></th><?php } ?>
		<?php if($opcion_update) { ?><th></th><?php } ?>
        <?php if($opcion_print) { ?><th></th><?php } ?>
	</tr>
    </thead>
	<?php if (isset($users)) foreach ($users as $user_item): ?>
	<tr>
		<td><?php echo $user_item->curp ?></td>
		<td><?php echo $user_item->nombre ?></td>
		<td><?php echo $user_item->apellido_paterno ?></td>
		<td><?php echo $user_item->apellido_materno ?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_TES?>/enrolamiento/view/<?php echo $user_item->id ?>" class="btn btn-small btn-primary">Detalles</a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES?>/enrolamiento/update/<?php echo $user_item->id ?>" class="btn btn-small btn-primary">Modificar</a></td><?php } ?>
        <?php if($opcion_print) { ?><td><a href="/<?php echo DIR_TES?>/enrolamiento/file_to_card/<?php echo $user_item->id ?>" class="btn btn-small btn-primary" target="_blank">Descargar</a></td><?php } ?>
	</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>
<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>

