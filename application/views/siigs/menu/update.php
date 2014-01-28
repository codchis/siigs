<script type="text/javascript">
DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';

$(document).ready(function(){
    obligatorios("updateMenu");
    
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
if(!empty($msgResult)) {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
}

if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    echo validation_errors();
    
    echo '<div id="alert"></div>';
    
    echo form_open(site_url().DIR_SIIGS.'/menu/update/'.$registro->id, array('onkeyup'=>'limpiaformulario(this.id)', 'onclick'=>'limpiaformulario(this.id)', 'id'=>'updateMenu')); ?>
        <div class="table table-striped">
        <table>
        <tr><td>Raíz: </td><td><?php echo form_dropdown('raiz', $menus, $registro->id_raiz); ?> </td></tr>
        <tr><td>Padre: </td><td><?php echo form_dropdown('padre', $menus, $registro->id_padre); ?> </td></tr>
        <tr><td>Ruta: </td><td><?php echo form_input( array('name'=>'ruta', 'maxlength'=>'200', 'value'=>$registro->ruta, 'required'=>'required', 'title'=>'requiere') ); ?> </td></tr>
        <tr><td>Nombre: </td><td><?php echo form_input( array('name'=>'nombre', 'value'=>$registro->nombre, 'required'=>'required', 'title'=>'requiere') ); ?> </td></tr>
        <tr><td>Entorno: </td><td><?php echo form_dropdown('entorno', $entornos, $registro->id_entorno); ?> </td></tr>
        <tr><td>Controlador: </td><td><?php echo form_dropdown('controlador', $controladores, $registro->id_controlador); ?> </td></tr>
        <tr><td>Atributos: </td><td><?php echo form_input( array('name'=>'atributo', 'maxlength'=>'300', 'value'=>$registro->atributo) ); ?> </td></tr>
        <tr><td colspan="2">
            <button type="submit" value="Actualizar" class="btn btn-primary" onclick="return validarFormulario('insertMenu')" >Actualizar <i class="icon-ok"></i></button>
            <button type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/menu/'" class="btn btn-primary" >Cancelar <i class="icon-chevron-left"></i></button>
        </td></tr>
        </table>
        </div>
    </form>
<?php
} else {
    echo '<div class="error">ERROR: Registro no encontrado</div><br /><br />';
    echo '<a href="'.site_url().DIR_SIIGS.'/menu/" class="btn btn-primary">Regresar al listado</a>';
}
?>