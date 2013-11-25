<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

$(document).ready(function(){
    $('select[name="entorno"]').change(function(e){
        $.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/controlador/',
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

<?php
if(!empty($msgResult))
    echo '<h3>'.$msgResult.'</h3>';

echo '<h2>'.$title.'</h2>';

echo validation_errors();

echo form_open(site_url().DIR_SIIGS.'/bitacora/create/'); ?>
<table>
    <tr><td>Usuario: </td><td><?php echo form_dropdown('usuario', $usuarios); ?> </td></tr>
    <tr><td>Parámetros: </td><td><?php echo form_input( array('name'=>'parametros', 'maxlength'=>'200') ); ?> </td></tr>
    <tr><td>Entorno: </td><td><?php echo form_dropdown('entorno', $entornos); ?> </td></tr>
    <tr><td>Controlador: </td><td><?php echo form_dropdown('controlador', $controladores); ?> </td></tr>
    <tr><td>Acción: </td><td><?php echo form_dropdown('accion', $acciones); ?> </td></tr>
    <tr><td colspan="2"><input type="submit" value="Guardar" />
        <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/bitacora/'" /></td></tr>
</table>
</form>
<a href="<?php echo site_url().DIR_SIIGS; ?>/bitacora">Regresar a listado</a>