<link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>

<script type="text/javascript">
DIR_TES = '<?php echo DIR_TES; ?>';

$(document).ready(function(){
    $('#paginador a').click(function(e){
        e.preventDefault();
        pag = $(this).attr('href');
        $('#form_filter_semana_nacional').attr('action', pag);
        $('#form_filter_semana_nacional').submit();
    });
    
	$("a#detalles").fancybox({
		'width'         : '50%',
		'height'        : '60%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',									
	});
    
});
</script>

<h2><?=$title;?></h2>

<?php 
$showInsert = Menubuilder::isGranted(DIR_TES.'::semana_nacional::insert');
$showUpdate = Menubuilder::isGranted(DIR_TES.'::semana_nacional::update');
$showDelete = Menubuilder::isGranted(DIR_TES.'::semana_nacional::delete');
$showView   = Menubuilder::isGranted(DIR_TES.'::semana_nacional::view');

echo form_open(site_url().DIR_TES.'/semana_nacional/index/'.$pag, array('name'=>'form_filter_semana_nacional', 'id'=>'form_filter_semana_nacional')); ?>
    <p><input type="hidden" name="filtrar" value="true" />
</form>

<?php
    if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
    
echo '<br />';

if($showInsert) 
    echo '<a href="'.site_url().DIR_TES.'/semana_nacional/insert/" class="btn btn-small btn-primary btn-icon">Registrar nuevo <i class="icon-plus"></i></a><br><br>';

echo form_open(site_url().DIR_TES.'/semana_nacional/', array('onsubmit'=>"return confirm('¿Esta seguro de eliminar los elementos seleccionados?');")); 
?>

<div class="table table-striped">
<table>
    <thead>
        <tr>
            <th>Descripci&oacute;n</th>
            <th>Fecha de inicio</th>
            <th>Fecha de fin</th>
            <?php if($showView) echo '<th></th>'; ?>
            <?php if($showUpdate) echo '<th></th>'; ?>
            <?php if($showDelete) echo '<th></th>'; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">';
                                        
                    echo '<td>'.htmlentities($fila->descripcion).'</td>
                    <td>'.formatFecha($fila->fecha_inicio, 'd-m-Y').'</td>
                    <td>'.formatFecha($fila->fecha_fin, 'd-m-Y').'</td>';
                    
                    if($showView) echo '<td><a id="detalles" href="'.site_url().DIR_TES.'/semana_nacional/view/'.$fila->id.'" class="btn btn-small btn-primary btn-icon">Detalles <i class="icon-eye-open"></i></a></td>';
                    
                    if($showUpdate) echo '<td><a href="'.site_url().DIR_TES.'/semana_nacional/update/'.$fila->id.'" class="btn btn-small btn-primary btn-icon">Modificar <i class="icon-pencil"></i></a></td>';
                    
                    if($showDelete) echo '<td><a href="'.site_url().DIR_TES.'/semana_nacional/delete/'.$fila->id.'"
                        onclick="if(confirm(\'¿Realmente desea eliminar el registro?\')) { return true; } else { return false; }" class="btn btn-small btn-primary btn-icon">Eliminar <i class="icon-remove"></i></a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="11"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr><td colspan="11">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>

</form>