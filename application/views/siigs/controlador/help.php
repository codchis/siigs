<script type="text/javascript" src="/resources/js/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    tinymce.init({selector:'textarea', language : 'es'});
});
</script>

<?php 
if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';

echo form_open(DIR_SIIGS.'/controlador/help/'.$id_controlador_accion); ?>
<textarea id="ayuda_controlador_accion" name="ayuda_controlador_accion"><?php echo (isset($_POST['ayuda_controlador_accion']) ? $_POST['ayuda_controlador_accion'] : $ayuda); ?></textarea>
<br />
<div align="center">
    <button type="submit" class="btn btn-primary">Guardar</button> &nbsp;
    <button type="button" class="btn btn-primary" onclick="parent.jQuery.fancybox.close();">Cancelar</button>
</div>
</form>