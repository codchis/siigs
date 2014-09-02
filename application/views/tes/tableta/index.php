<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<style type="text/css">
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>

<h2><?=$title;?></h2>

<?php
    if(!empty($msgResult))
        echo '<div class="'.($clsResult ? $clsResult : 'info').'">'.$msgResult.'</div>';
    
    echo validation_errors();
    
    $showInsert = Menubuilder::isGranted(DIR_TES.'::tableta::insert');
    $showUpdate = Menubuilder::isGranted(DIR_TES.'::tableta::update');
    $showDelete = Menubuilder::isGranted(DIR_TES.'::tableta::delete');
    $showView   = Menubuilder::isGranted(DIR_TES.'::tableta::view');
    
    $estad[''] = 'Seleccione una opción';
    foreach($estados as $row) {
        $estad[$row->id] = $row->descripcion;
    }
    
    echo form_open(site_url().DIR_TES.'/tableta/index/'); ?>
<div class="table table-striped">
<table>
    <tr>
        <td>Estado:</td>
        <td><?php echo form_dropdown('estados', $estad); ?></td>
        <td>Jurisdicción:</td>
        <td><?php echo form_dropdown('juris', $jurisdicciones); ?></td>
    </tr>
    <tr>
        <td>Municipio:</td>
        <td><?php echo form_dropdown('municipios', $municipios); ?></td>
        <td>Localidad:</td>
        <td><?php echo form_dropdown('localidades', $localidades); ?></td>
    </tr>
    <tr>
        <td>UM:</td>
        <td><?php echo form_dropdown('ums', $unidades); ?></td>
        <td></td>
        <td><button type="submit" name="filtrar" class="btn btn-primary">Buscar <i class="icon-search"></i> </button></td>
    </tr>
</table>
</div>
</form>

<?php 
if($showInsert) { ?>
    <label>Registrar direcciones MAC desde un archivo de texto</label>
    <?php echo form_open_multipart(site_url().DIR_TES.'/tableta/uploadFile');?>
        <input type="file" name="archivo" size="60" class="btn btn-primary" />
        <button type="submit" class="btn btn-primary" >Subir Archivo <i class="icon-upload"></i></button>
    </form>
    <?php 
}

echo form_open(site_url().DIR_TES.'/tableta/', array('onsubmit'=>"return confirm('¿Esta seguro de eliminar los elementos seleccionados?');")); ?>

<?php 
    if($showInsert) 
        echo '<button type="button" name="registrarTableta" id="registrarTableta" class="btn btn-primary">
            Registrar nuevo <i class="icon-plus"></i></button> ';

    if($showDelete) 
        echo '<button type="submit" class="btn btn-primary">Eliminar Seleccionados <i class="icon-remove"></i></button>'; 
?>
<br /><br />
<div class="table table-striped">
<table width="100%" border="0" id="tabla">
    <thead>
        <tr>
            <?php if($showDelete) echo '<th></th>'; ?>
            <th>MAC</th>
            <th>Versi&oacute;n</th>
            <th>Ultima Actualizaci&oacute;n</th>
            <th>Status</th>
            <th>Tipo Censo</th>
            <th>Unidad Médica</th>
            <th>Usuarios</th>
            <?php if($showView) echo '<th></th>'; ?>
            <?php if($showUpdate) echo '<th></th>'; ?>
            <?php if($showDelete) echo '<th></th>'; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">';
                
                if($showDelete) echo '<td><input type="checkbox" name="registroEliminar[]" value="'.$fila->id.'" /></td>';
                
                echo '<td>'.$fila->mac.'</td>
                    <td>'.$fila->id_version.'</td>
                    <td>'.htmlentities(formatFecha($fila->ultima_actualizacion)).'</td>
                    <td>'.htmlentities($fila->status).'</td>
                    <td>'.htmlentities($fila->tipo_censo).'</td>
                    <td><a href="/'.DIR_TES.'/tree/create/TES/Unidad Médica/1/radio/0/id_unidad_medica/nombre_unidad_medica/1/1/'.
                        urlencode(json_encode(array(NULL))).'/'.urlencode(json_encode(array(1,2,3,4,5))).'" '.
                        'class="agregarUM '.(($fila->id_asu_um==0  || !isset($unidades_medicas[$fila->id_asu_um])) ? 'btn btn-small btn-primary btn-icon' : '').'" '
                        . 'data-tipocenso="'.$fila->id_tipo_censo.'" data-um="'.$fila->id_asu_um.'" data-tableta="'.$fila->id.'" data-periodo="'.$fila->periodo_esq_inc.'">'.
                        (($fila->id_asu_um==0 || !isset($unidades_medicas[$fila->id_asu_um]))? 'Asignar  <i class="icon-home"></i>' : $unidades_medicas[$fila->id_asu_um]).'</a></td>
                    <td><a href="'.site_url().DIR_TES.'/usuario_tableta/index/'.$fila->id.'" class="btn btn-small btn-primary btn-icon">'.($fila->usuarios_asignados==0 ? 'Asignar' : 'Ver').' <i class="icon-user"></i></a></td>';
                    
                    if($showView) echo '<td><a id="detalles" href="'.site_url().DIR_TES.'/tableta/view/'.$fila->id.'" class="btn btn-small btn-primary btn-icon">Detalles <i class="icon-eye-open"></i></a></td>';
                    
                    if($showUpdate) echo '<td><a href="'.site_url().DIR_TES.'/tableta/update/'.$fila->id.'" class="btn btn-small btn-primary btn-icon">Modificar <i class="icon-pencil"></i></a></td>';
                    
                    if($showDelete) echo '<td><a href="'.site_url().DIR_TES.'/tableta/delete/'.$fila->id.'"
                        onclick="if(confirm(\'¿Realmente desea eliminar el registro?\')) { return true; } else { return false; }" class="btn btn-small btn-primary btn-icon">Eliminar <i class="icon-remove"></i></a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="11"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr><td colspan="11">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>
</form>

<script type="text/javascript">
actionSetUM = '<?php echo site_url().DIR_TES.'/tableta/setUM/'; ?>';

$(function() {
    DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';
    var tipo_censo = $("#id_tipo_censo"),
        unidad_medica = $("#id_unidad_medica"),
        periodo = $("#periodo_esq_inc"),
        mac = $("#mac");

    allFields = $([]).add(tipo_censo).add(unidad_medica).add(mac).add(periodo);
    tips = $(".validateTips");

    function updateTips(t) {
        tips.text(t).addClass("ui-state-highlight");
        setTimeout(function() {
            tips.removeClass("ui-state-highlight", 1500);
        }, 500);
    }

    function checkSelect(object, name) {
        if( object.val() == 0 ) {
            updateTips("Debe selecccionar un "+name);
            object.addClass( "ui-state-error" );
            return false;
        } else {
            return true;
        }
    }

    $("#dialog-form").dialog({
        autoOpen: false,
        height: 380,
        width: 600,
        modal: true,
        buttons: {
            "OK": function() {
                var validOK = true;
                allFields.removeClass("ui-state-error");

                validOK = validOK && checkSelect(tipo_censo, "Tipo de censo");
                validOK = validOK && checkSelect(unidad_medica, "Unidad Médica");

                if(validOK) {
                    $('#form-addUM').submit();
                }
            },
            Cancelar: function() {
                $(this).dialog("close");
                allFields.val("").removeClass("ui-state-error");
            }
        },
    });
    
    $("#dialog-nuevaTableta").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Guardar": function() {
                var validOK = true;
                allFields.removeClass("ui-state-error");

                validOK = validOK && checkSelect(mac, "MAC");

                if(validOK) {
                    $('#form-addTableta').submit();
                }
            },
            Cancelar: function() {
                $(this).dialog("close");
                allFields.val("").removeClass("ui-state-error");
            }
        },
        close: function() {
          allFields.val("").removeClass("ui-state-error");
        }
    });

    $(".agregarUM").click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        
        $("#id_tipo_censo option[value="+ $(this).data('tipocenso') +"]").attr("selected",true);
        $("#id_unidad_medica").val($(this).data('um'));
        $("#periodo_esq_inc").val($(this).data('periodo'));
        $("#show_um").attr('href', $(this).attr('href'));
        
        $("#form-addUM").attr('action', actionSetUM+$(this).data('tableta'));
        
        if($(this).data('um') == '')
            $("#nombre_unidad_medica").val('');
        else
        {
            $.ajax({
                type: "POST",
                url: '/'+DIR_SIIGS+'/raiz/getDataTreeFromId',
                data: {
                    "claves": [$(this).data('um')], 
                    "desglose": 3 
                },
                success: function(response) {
                        $("#nombre_unidad_medica").val(response[0].descripcion);
                },
                dataType: 'json'
            });
        }
        $("#dialog-form").dialog("open");
    });
    
    $("#registrarTableta").click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        
        $("#dialog-nuevaTableta").dialog("open");
    });
    
    $("#show_um").fancybox({
        'width'         : '50%',
        'height'        : '80%',
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic',
        'type'			: 'iframe',
    });

    $("#show_um").button();
    
    $("a#detalles").fancybox({
		'width'         : '50%',
		'height'        : '60%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',									
	}); 
    
    $('select[name="estados"]').change(function(e){
    	$('select[name="juris"]')
        .find('option')
        .remove()
        .end()
        .append('<option value="">Seleccione una opcion</option>')
        .val('')
	;
       	$('select[name="municipios"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
    	;
    	$('select[name="localidades"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
        $.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/2/'+$('select[name="estados"]').val(),
            dataType: 'json'
        }).done(function(juris){
            $.each(juris, function(index) {
                option = $('<option />');
                option.val(juris[index].id);
                option.text(juris[index].descripcion);

                $('select[name="juris"]').append(option);
            });
        });
    });
    
    $('select[name="juris"]').change(function(e){
    	$('select[name="municipios"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
    	;
    	$('select[name="localidades"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
        $.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/3/'+$('select[name="juris"]').val(),
            dataType: 'json'
        }).done(function(municipios){
            $.each(municipios, function(index) {
                option = $('<option />');
                option.val(municipios[index].id);
                option.text(municipios[index].descripcion);

                $('select[name="municipios"]').append(option);
            });
        });
    });
    
    $('select[name="municipios"]').change(function(e){
	   	$('select[name="localidades"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
		$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/4/'+$('select[name="municipios"]').val(),
            dataType: 'json'
        }).done(function(localidades){
            $.each(localidades, function(index) {
                option = $('<option />');
                option.val(localidades[index].id);
                option.text(localidades[index].descripcion);

                $('select[name="localidades"]').append(option);
            });
        });
    });
    
    $('select[name="localidades"]').change(function(e){
		$('select[name="ums"]')
	        .find('option')
	        .remove()
	        .end()
	        .append('<option value="">Seleccione una opcion</option>')
	        .val('')
		;
    	$.ajax({
            type: 'POST',
            url:  '/'+DIR_SIIGS+'/raiz/getDataKeyValue/1/5/'+$('select[name="localidades"]').val(),
            dataType: 'json'
        }).done(function(ums){
            $.each(ums, function(index) {
                option = $('<option />');
                option.val(ums[index].id);
                option.text(ums[index].descripcion);

                $('select[name="ums"]').append(option);
            });
        });
    });
    
});
</script>
  
<div id="dialog-form" title="Asignar unidad médica a la tableta">
    <p class="validateTips"></p>
    <form name="form-addUM" id="form-addUM" method="post" action="<?php echo site_url().DIR_TES.'/tableta/setUM/'; ?>">
        <fieldset>
            <label for="id_tipo_censo">Tipo Censo</label>
            <select name="id_tipo_censo" id="id_tipo_censo" class="text ui-widget-content ui-corner-all">
                <option value="0">Seleccione una opción</option>
                <?PHP
                foreach ($tipos_censo as $tipo) {
                    echo '<option value="'.$tipo->id.'">'.$tipo->descripcion.'</option>';
                }
                ?>
            </select>
            <br />
            <label for="nombre_unidad_medica">Unidad Médica</label>
            <input type="text" name="nombre_unidad_medica" id="nombre_unidad_medica" size="60" value="" readonly />
            <a href='/<?php echo DIR_TES?>/tree/create/TES/Unidad Medica/1/radio/0/id_unidad_medica/nombre_unidad_medica/1/1/
                <?php echo urlencode(json_encode(array(NULL)));?>/<?php echo urlencode(json_encode(array(1,2,3,4,5)));?>' 
               id="show_um">Seleccionar Unidad Médica</a>
            <input type="hidden" name="id_unidad_medica" id="id_unidad_medica" /><br />
            <label for="periodo_esq_inc">Periodo de esquema incompleto</label>
            <input type="text" name="periodo_esq_inc" id="periodo_esq_inc" size="60" value="" />
        </fieldset>
    </form>
</div>

<div id="dialog-nuevaTableta" title="Registrar dirección MAC">
    <p class="validateTips"></p>
    <form name="form-addTableta" id="form-addTableta" method="post" action="<?php echo site_url().DIR_TES.'/tableta/insert/'; ?>">
        <fieldset>
            <label for="mac">MAC</label>
            <input type="text" name="mac" id="mac" maxlength="20" />
        </fieldset>
    </form>
</div>