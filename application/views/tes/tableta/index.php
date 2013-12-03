<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->

<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>

<style type="text/css">
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>

<h2><?=$title;?></h2>
<br />
<?php
    if(!empty($msgResult))
        echo '<strong>'.$msgResult.'</strong>';
    
    echo validation_errors();
?>
<br />

<?php echo form_open(site_url().DIR_TES.'/tableta/', array('onsubmit'=>"return confirm('Esta seguro de eliminar los elementos seleccionados');")); ?>

<table border="1">
    <thead>
        <tr>
            <th></th>
            <th>MAC</th>
            <th>Versi&oacute;n</th>
            <th>Ultima Actualizaci&oacute;n</th>
            <th>Status</th>
            <th>Tipo Censo</th>
            <th>Unidad Médica</th>
            <th>Usuarios</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(!empty($registros)) {
            foreach ($registros as $fila) {
                echo '<tr id="'.$fila->id.'">
                    <td><input type="checkbox" name="registroEliminar[]" value="'.$fila->id.'" /></td>
                    <td>'.$fila->mac.'</td>
                    <td>'.$fila->version.'</td>
                    <td>'.htmlentities(formatFecha($fila->ultima_actualizacion)).'</td>
                    <td>'.htmlentities($fila->status).'</td>
                    <td>'.htmlentities($fila->tipo_censo).'</td>
                    <td><a href="/'.DIR_TES.'/Tree/tree/TES/Unidad Médica/1/radio/0/id_unidad_medica/nombre_unidad_medica/1/1/'.
                        urlencode(json_encode(array(null))).'/'.urlencode(json_encode(array(($fila->id_asu_um==0 ? null : $fila->id_asu_um)))).'" '.
                        'class="agregarUM" data-tipocenso="'.$fila->id_tipo_censo.'" data-um="'.$fila->id_asu_um.'">'.
                        ($fila->id_asu_um==0 ? 'No asignado' : $unidades_medicas[$fila->id_asu_um]).'</a></td>
                    <td><a href="'.site_url().DIR_TES.'/usuario_tableta/index/'.$fila->id.'">'.($fila->usuarios_asignados==0 ? 'No asignados' : 'Ver').'</a></td>
                    <td><a href="'.site_url().DIR_TES.'/tableta/view/'.$fila->id.'">Ver</a></td>
                    <td><a href="'.site_url().DIR_TES.'/tableta/update/'.$fila->id.'">Modificar</a></td>
                    <td><a href="'.site_url().DIR_TES.'/tableta/delete/'.$fila->id.'"
                        onclick="if(confirm(\'Realmente desea eliminar el registro\')) { return true; } else { return false; }">Eliminar</a></td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="7"><div align="center">No se encontraron registros en la busqueda</div></td></tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>

<input type="submit" value="Eliminar Seleccionados" />

</form>
<br />

<input type="button" name="registrarTableta" id="registrarTableta" value="Registrar nuevo" />

<br /><br />

<label>Subir un archivo con la lista de direcciones MAC</label>
<?php echo form_open_multipart(site_url().DIR_TES.'/tableta/uploadFile');?>
    <input type="file" name="archivo" size="60" />
    <input type="submit" value="Subir Archivo" />
</form>

<script type="text/javascript">
$(function() {
    DIR_SIIGS = '<?php echo DIR_SIIGS; ?>';
    var tipo_censo = $("#id_tipo_censo"),
        unidad_medica = $("#id_unidad_medica"),
        mac = $("#mac");

    allFields = $([]).add(tipo_censo).add(unidad_medica).add(mac);
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
        height: 350,
        width: 500,
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
        $("#show_um").attr('href', $(this).attr('href'));
        
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
});
</script>
  
<div id="dialog-form" title="Asignar unidad médica a la tableta">
    <p class="validateTips"></p>
    <form name="form-addUM" id="form-addUM" method="post" action="<?php echo site_url().DIR_TES.'/tableta/add_um/'; ?>">
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
            <input type="text" name="nombre_unidad_medica" id="nombre_unidad_medica" size="50" value="" readonly />
            <a href='/<?php echo DIR_TES?>/Tree/tree/TES/Unidad Medica/1/radio/0/id_unidad_medica/nombre_unidad_medica/1/1/
                <?php echo urlencode(json_encode(array(null)));?>/<?php echo urlencode(json_encode(array(1020)));?>' 
               id="show_um">Seleccionar Unidad Médica</a>
            <input type="hidden" name="id_unidad_medica" id="id_unidad_medica" />
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