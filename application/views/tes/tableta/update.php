<?php
if(!empty($msgResult)) {
    echo '<h3>'.$msgResult.'</h3>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    echo validation_errors();
    ?>

    <?php echo form_open(site_url().DIR_TES.'/tableta/update/'.$registro->id); ?>
        <table>
        <tr><td>MAC: </td><td><?php echo form_input( array('name'=>'mac', 'maxlength'=>'20', 'value'=>$registro->mac) ); ?> </td></tr>
        <tr><td>Estado tableta: </td><td><?php echo form_dropdown('status', $status, $registro->id_tes_estado_tableta); ?> </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Actualizar" />
            <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_TES; ?>/tableta/'" />
        </td></tr>
        <table>
    </form>

    <a href="<?php echo site_url().DIR_TES; ?>/tableta">Regresar a listado</a>
<?php
} else {
    echo 'ERROR: Registro no encontrado';
}
?>