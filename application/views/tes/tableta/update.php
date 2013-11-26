<?php
if(!empty($msgResult)) {
    echo '<h3>'.$msgResult.'</h3>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    echo validation_errors();
    ?>

    <?php echo form_open(site_url().DIR_SIIGS.'/menu/update/'.$registro->id); ?>
        <table>
        <tr><td>Raíz: </td><td><?php echo form_dropdown('raiz', $menus, $registro->id_raiz); ?> </td></tr>
        <tr><td>Padre: </td><td><?php echo form_dropdown('padre', $menus, $registro->id_padre); ?> </td></tr>
        <tr><td>Ruta: </td><td><?php echo form_input( array('name'=>'ruta', 'maxlength'=>'200', 'value'=>$registro->ruta) ); ?> </td></tr>
        <tr><td>Nombre: </td><td><?php echo form_input( array('name'=>'nombre', 'value'=>$registro->nombre) ); ?> </td></tr>
        <tr><td>Entorno: </td><td><?php echo form_dropdown('entorno', $entornos, $registro->id_entorno); ?> </td></tr>
        <tr><td>Controlador: </td><td><?php echo form_dropdown('controlador', $controladores, $registro->id_controlador); ?> </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Actualizar" />
            <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/menu/'" />
        </td></tr>
        <table>
    </form>

    <a href="<?php echo site_url().DIR_SIIGS; ?>/menu">Regresar a listado</a>
<?php
} else {
    echo 'ERROR: Registro no encontrado';
}
?>