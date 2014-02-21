<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

$(document).ready(function(){
    obligatorios("insertMenu");
    
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

echo '<div id="alert"></div>';

echo form_open(site_url().DIR_SIIGS.'/menu/insert/', array('onkeyup'=>'limpiaformulario(this.id)', 'onclick'=>'limpiaformulario(this.id)', 'id'=>'insertMenu')); ?>
<div class="table table-striped">
<table>
    <tr><td>Raíz: </td><td><?php echo form_dropdown('raiz', $menus, (!empty($menuSeleccionado) ? $menuSeleccionado->id_raiz : null) ); ?> </td></tr>
    <tr><td>Padre: </td><td><?php echo form_dropdown('padre', $menus, (!empty($menuSeleccionado) ? $menuSeleccionado->id_padre : null) ); ?> </td></tr>
    <tr><td>Ruta: </td><td><?php echo form_input( array('name'=>'ruta', 'maxlength'=>'200', 'required'=>'required', 'title'=>'requiere') ); ?> </td></tr>
    <tr><td>Nombre: </td><td><?php echo form_input( array('name'=>'nombre', 'required'=>'required', 'title'=>'requiere') ); ?> </td></tr>
    <tr><td>Entorno: </td><td><?php echo form_dropdown('entorno', $entornos); ?> </td></tr>
    <tr><td>Controlador: </td><td><?php echo form_dropdown('controlador', $controladores); ?> </td></tr>
    <tr><td>Atributo: </td><td><?php echo form_input( array('name'=>'atributo', 'maxlength'=>'300') ); ?> </td></tr>
    <tr align="center"><td colspan="2">
        <button type="submit" value="Guardar" class="btn btn-primary" onclick="return validarFormulario('insertMenu')" >Guardar <i class="icon-hdd"></i></button>
        <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/menu/'" class="btn btn-primary" >Cancelar <i class="icon-arrow-left"></i></button>
    </td></tr>
</table>
</form>
</div>