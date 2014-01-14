<?php
if(!empty($msgResult)) {
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    echo validation_errors();
    ?>

    <?php echo form_open(site_url().DIR_TES.'/tableta/update/'.$registro->id); ?>
        <div class="table table-striped">
        <table>
        <tr><td>MAC: </td><td><?php echo form_input( array('name'=>'mac', 'maxlength'=>'20', 'value'=>$registro->mac) ); ?> </td></tr>
        <tr><td>Estado tableta: </td><td><?php echo form_dropdown('status', $status, $registro->id_tes_estado_tableta); ?> </td></tr>
        <tr><td>Periodo de esquema incompleto: </td><td><?php echo form_input( array('name'=>'periodo_esq_inc', 'maxlength'=>'20', 'value'=>$registro->periodo_esq_inc) ); ?> </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Actualizar" class="btn btn-primary" />
            <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_TES; ?>/tableta/'" class="btn btn-primary" />
        </td></tr>
        </table>
        </div>
    </form>

    <a href="<?php echo site_url().DIR_TES; ?>/tableta" class="btn btn-primary">Regresar a listado</a>
<?php
} else {
    echo '<div class="error">ERROR: Registro no encontrado</div>';
}
?>