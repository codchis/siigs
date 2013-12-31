<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

$(document).ready(function(){
    $('#paginador a').click(function(e){
        e.preventDefault();
        pag = $(this).attr('href');
        $('#form_filter_menu').attr('action', pag);
        $('#form_filter_menu').submit();
    });

    $('#btnFiltrar').click(function(e){
        // Eliminar la pagina de la url del action
        action = $('#form_filter_menu').attr('action');
        action = action.replace(/\d+(\/)*$/,'');

        $('#form_filter_menu').attr('action',action);
        $('#form_filter_menu').submit();
    });

    $('select[name="entorno"]').change(function(e){
        $.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/controlador',
            data: 'id_entorno='+$(this).val(),
            dataType: 'json'
        }).done(function(controladores){
            $('select[name="controlador"] > :not(option[value=0])').remove();
            
            $.each(controladores, function(index) {
                option = $('<option />');
                option.val(controladores[index].id);
                option.text(controladores[index].nombre);

                $('select[name="controlador"]').append(option);
            });
        });
    });

});
</script>

<style>
    select[name=raiz] {
        margin-bottom: auto !important;
    }
</style>

<?php
    $showInsert = Menubuilder::isGranted(DIR_SIIGS.'::menu::insert');
    $showUpdate = Menubuilder::isGranted(DIR_SIIGS.'::menu::update');
    $showDelete = Menubuilder::isGranted(DIR_SIIGS.'::menu::delete');
    $showView   = Menubuilder::isGranted(DIR_SIIGS.'::menu::view');
?>

<h2><?=$title;?></h2>

<?php echo form_open(site_url().DIR_SIIGS.'/menu/index/'.$pag, array('name'=>'form_filter_menu', 'id'=>'form_filter_menu')); ?>
    <input type="hidden" name="filtrar" value="true" />
    Raíz: <?php echo form_dropdown('raiz', $menus); ?>
    <input type="button" name="btnFiltrar" id="btnFiltrar" value="Filtrar" class="btn btn-primary" /> 
</form>

<?php echo form_open(site_url().DIR_SIIGS.'/menu/', array('onsubmit'=>"return confirm('Esta seguro de eliminar los elementos seleccionados');"));

    if($showDelete)
        echo '<input type="submit" value="Eliminar Seleccionados" class="btn btn-primary" /> ';

    if($showInsert)
        echo '<input type="button" name="crear" value="Crear nuevo" onclick="location.href=\''.site_url().DIR_SIIGS.'/menu/insert\'" class="btn btn-primary"/>';
?><br>

<?php
    if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
?>

<div class="table table-striped">
<table>
    <thead>
        <tr>
            <?php if($showDelete) echo '<th></th>'; ?>
            <th><h2>Raiz</h2></th>
            <th><h2>Padre</h2></th>
            <th><h2>Nombre</h2></th>
            <th><h2>Ruta</h2></th>
            <th><h2>Controlador</h2></th>
            <?php if($showView) echo '<th><h2></h2></th>'; ?>
            <?php if($showUpdate) echo '<th><h2></h2></th>'; ?>
            <?php if($showDelete) echo '<th><h2></h2></th>'; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">';
                
                    if($showDelete) echo '<td><input type="checkbox" name="registroEliminar[]" value="'.$fila->id.'" /></td>';
                    
                    echo '<td>'.$fila->nombre_raiz.'</td>
                    <td>'.$fila->nombre_padre.'</td>
                    <td>'.htmlentities($fila->nombre).'</td>
                    <td>'.htmlentities($fila->ruta).'</td>
                    <td><a href="'.site_url().DIR_SIIGS.'/controlador/view/'.$fila->id_controlador.'">'.htmlentities($fila->nombre_controlador).'</a></td>';
                    if($showView) echo '<td><a href="'.site_url().DIR_SIIGS.'/menu/view/'.$fila->id.'" class="btn btn-small btn-primary">Ver</a></td>';
                    if($showUpdate) echo '<td><a href="'.site_url().DIR_SIIGS.'/menu/update/'.$fila->id.'" class="btn btn-small btn-primary">Modificar</a></td>';
                    if($showDelete) echo '<td><a href="'.site_url().DIR_SIIGS.'/menu/delete/'.$fila->id.'"
                        onclick="if(confirm(\'Realmente desea eliminar el registro\')) { return true; } else { return false; }" class="btn btn-small btn-primary">Eliminar</a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr><td colspan="9">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>

</form>
