<?php
if(!empty($msgResult)) {
    echo '<h3>'.$msgResult.'</h3>';
} else if(is_object($registro)) {
    echo '<h2>'.$title.'</h2>';
    echo validation_errors();
    ?>

    <?php echo form_open(site_url().DIR_SIIGS.'/bitacora/update/'.$registro->id); ?>
        Usuario: <?php echo form_dropdown('usuario', $usuarios, $registro->id_usuario); ?> <br />
        fecha_hora: <input type="text" name="fecha_hora" value="<?php echo $registro->fecha_hora; ?>"/> <br />
        Parámetros: <input type="text" name="parametros" value="<?php echo $registro->parametros; ?>"/> <br />
        id_controlador_accion: <input type="text" name="controlador_accion" value="<?php echo $registro->id_controlador_accion; ?>"/> <br />
        
        <input type="submit" value="Actualizar" />
        <input type="button" name="cancelar" value="Cancelar" onclick="location.href='<?php echo site_url().DIR_SIIGS; ?>/bitacora/'" />
    </form>

    <a href="<?php echo site_url().DIR_SIIGS; ?>/bitacora">Regresar a listado</a>
<?php
} else {
    echo 'ERROR: Registro no encontrado';
}
?>