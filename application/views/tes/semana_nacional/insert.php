<link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
$(document).ready(function(){
	$("[name=fecha_fin]").datepicker(optionsFecha);
    $("[name=fecha_inicio]").datepicker(optionsFecha);
});
</Script>
<?php
if(!empty($msgResult))
    echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';

echo '<h2>'.$title.'</h2>';

echo validation_errors();

echo form_open(site_url().DIR_TES.'/semana_nacional/insert/'); ?>

    <div class="table table-striped">
        <table>
            <tr><td><strong>Descripci&oacute;n</strong></td><td>
                <?php echo form_input( array('name'=>'descripcion', 'maxlength'=>'45') ); ?>
        </td></tr>
            <tr><td><strong>Fecha de inicio</strong></td><td>
                 <?php echo form_input( array('name'=>'fecha_inicio') ); ?>
        </td></tr>
            <tr><td><strong>Fcha de fin</strong></td><td>
                 <?php echo form_input( array('name'=>'fecha_fin') ); ?>
        </td></tr>
        <tr><td colspan="2"><button type="submit" class="btn btn-primary">Guardar <i class="icon-hdd"></i></button>
        <button type="button" name="cancelar" onclick="location.href='<?php echo site_url().DIR_TES; ?>/semana_nacional/'" class="btn btn-primary">Cancelar <i class="icon-arrow-left"></i></button></td></tr>
    </table></div>

</form>