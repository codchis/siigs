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

});
</script>

<h2><?=$title;?></h2>

<fieldset>
    <legend><strong>Opciones de filtrado</strong></legend>
    <?php echo form_open(site_url().DIR_SIIGS.'/bitacora/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
        <p><input type="hidden" name="filtrar" value="true" />

        Usuario:
        <?php  if(isset($usuarios)) echo form_dropdown('usuario', $usuarios); ?>
        Fecha:       <input type="date" name="fechaIni" value="<?php echo isset($fechaIni) ? $fechaIni: ''; ?>" size="10" placeholder="desde" />
                     <input type="date" name="fechaFin" value="<?php echo isset($fechaFin) ? $fechaFin: ''; ?>" size="10" placeholder="hasta"/> </p>
        <p>Entorno:
            <?php  if(isset($entornos)) echo form_dropdown('entorno', $entornos); ?>
        Controlador:
            <?php  if(isset($controladores)) echo  form_dropdown('controlador', $controladores);?>
        Acción:
            <?php  if(isset($acciones)) echo  form_dropdown('accion', $acciones);?> <br /><br /></p>
        <input type="button" name="btnFiltrar" id="btnFiltrar" value="Filtrar" />
    </form>
</fieldset>

<br />
<?php
    if(!empty($msgResult))
        echo '<strong>'.$msgResult.'</strong>';
?>
<br />

<?php echo form_open(site_url().DIR_SIIGS.'/bitacora/', array('onsubmit'=>"return confirm('Esta seguro de eliminar los elementos seleccionados');")); ?>

<table border="1">
    <thead>
        <tr>
            <th></th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Parámetros</th>
            <th>Entorno</th>
            <th>Controlador</th>
            <th>Acción</th>
            <th>Ver detalles</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">
                    <td><input type="checkbox" name="registroEliminar[]" value="'.$fila->id.'" /></td>
                    <td>'.$fila->usuario.'</td>
                    <td>'.$fila->nombre.' '.$fila->apellido_paterno.' '.$fila->apellido_materno.'</td>
                    <td>'.htmlentities($fila->fecha_hora).'</td>
                    <td>'.htmlentities($fila->parametros).'</td>
                    <td>'.htmlentities($fila->entorno).'</td>
                    <td>'.htmlentities($fila->controlador).'</td>
                    <td>'.htmlentities($fila->accion).'</td>
                    <td><a href="'.site_url().DIR_SIIGS.'/bitacora/view/'.$fila->id.'">Ver</a></td>
                    <td><a href="'.site_url().DIR_SIIGS.'/bitacora/update/'.$fila->id.'">Modificar</a></td>
                    <td><a href="'.site_url().DIR_SIIGS.'/bitacora/delete/'.$fila->id.'"
                        onclick="if(confirm(\'Realmente desea eliminar el registro\')) { return true; } else { return false; }">Eliminar</a></td>
                </tr>';
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

<br />
<input type="submit" value="Eliminar Seleccionados" />

</form>
<br />

<!--<input type="button" name="crear" value="Crear nuevo" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/bitacora/insert'" />-->