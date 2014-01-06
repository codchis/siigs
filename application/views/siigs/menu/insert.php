<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

$(document).ready(function(){
    $('select[name="entorno"]').change(function(e){
        $('select[name="controlador"] > option[value=0]').text('Cargando datos...');
        
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
            
            $('select[name="controlador"] > option[value=0]').text('Elegir');
        });
    });

});
</script>

<?php
if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';

echo '<h2>'.$title.'</h2>';

echo validation_errors();

echo form_open(site_url().DIR_SIIGS.'/menu/insert/'); ?>
<div class="table table-striped">
<table>
    <tr><td>Raíz: </td><td><?php echo form_dropdown('raiz', $menus); ?> </td></tr>
    <tr><td>Padre: </td><td><?php echo form_dropdown('padre', $menus); ?> </td></tr>
    <tr><td>Ruta: </td><td><?php echo form_input( array('name'=>'ruta', 'maxlength'=>'200') ); ?> </td></tr>
    <tr><td>Nombre: </td><td><?php echo form_input( array('name'=>'nombre') ); ?> </td></tr>
    <tr><td>Entorno: </td><td><?php echo form_dropdown('entorno', $entornos); ?> </td></tr>
    <tr><td>Controlador: </td><td><?php echo form_dropdown('controlador', $controladores); ?> </td></tr>
    <tr align="center"><td colspan="2"><input type="submit" value="Guardar" class="btn btn-primary" />
        <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/menu/'" class="btn btn-primary" /></td></tr>
</table>
</form>
</div>