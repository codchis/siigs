<link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<script src="/resources/ui/jquery-ui-1.8.17.custom.js" type="text/javascript"></script>	
    
<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

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
    
    $("#fechaIni").datepicker(optionsFecha);
    $("#fechaFin").datepicker(optionsFecha);
    
    $("#limpiaFecha").click(function(){
        $("#fechaIni").val('');
        $("#fechaFin").val('');
    });
    
});
</script>

<h2><?=$title;?></h2>

<?php 
$showDelete = false;//Menubuilder::isGranted(DIR_SIIGS.'::bitacora::delete');
$showView   = Menubuilder::isGranted(DIR_SIIGS.'::bitacora::view');
?>

    <?php echo form_open(site_url().DIR_SIIGS.'/bitacora/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
        <p><input type="hidden" name="filtrar" value="true" />
        Usuario:
        <?php  if(isset($usuarios)) echo form_dropdown('usuario', $usuarios); ?>
        Fecha: <input type="text" name="fechaIni" id="fechaIni" value="<?php echo isset($fechaIni) ? $fechaIni: ''; ?>" size="8" placeholder="desde" />
               <input type="text" name="fechaFin" id="fechaFin" value="<?php echo isset($fechaFin) ? $fechaFin: ''; ?>" size="8" placeholder="hasta" /> 
               <input type="button" value="Limpiar Fechas" id="limpiaFecha" class="btn btn-mini btn-primary" />
        </p>
        <p>Entorno:
            <?php  if(isset($entornos)) echo form_dropdown('entorno', $entornos); ?>
        Controlador:
            <?php  if(isset($controladores)) echo  form_dropdown('controlador', $controladores);?>
        Acción:
            <?php  if(isset($acciones)) echo  form_dropdown('accion', $acciones);?></p>
        <input type="button" name="btnFiltrar" id="btnFiltrar" value="Filtrar" class="btn btn-primary" />
    </form>

<?php
    if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
?>
<br />

<?php echo form_open(site_url().DIR_SIIGS.'/bitacora/', array('onsubmit'=>"return confirm('Esta seguro de eliminar los elementos seleccionados');")); ?>

<div class="table table-striped">
<table>
    <thead>
        <tr>
            <?php if($showDelete) echo '<th></th>'; ?>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Parámetros</th>
            <th>Entorno</th>
            <th>Controlador</th>
            <th>Acción</th>
            <?php if($showView) echo '<th></th>'; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">';
                    
                    if($showDelete) echo '<td><input type="checkbox" name="registroEliminar[]" value="'.$fila->id.'" /></td>';
                    
                    echo '<td>'.$fila->usuario.'</td>
                    <td>'.$fila->nombre.' '.$fila->apellido_paterno.' '.$fila->apellido_materno.'</td>
                    <td>'.htmlentities(formatFecha($fila->fecha_hora)).'</td>
                    <td>'.htmlentities($fila->parametros).'</td>
                    <td>'.htmlentities($fila->entorno).'</td>
                    <td>'.htmlentities($fila->controlador).'</td>
                    <td>'.htmlentities($fila->accion).'</td>';
                    if($showView) echo '<td><a href="'.site_url().DIR_SIIGS.'/bitacora/view/'.$fila->id.'" class="btn btn-small btn-primary">Detalles</a></td>';
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