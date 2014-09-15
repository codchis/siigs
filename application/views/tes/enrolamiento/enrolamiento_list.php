<link href="/resources/css/alert.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
<script type="text/javascript" src="/resources/js/libNFC.js"></script>

<script type="text/javascript">
tesNFC = new libNFC();
tesNFC.connect();

$(document).ready(function()
{
	<?php if(!empty($id)){?>
	if(confirm("<?php echo $msgResult ?>\n\n ¿Deseas guardarlo en la tarjeta?"))
	{
		$.fancybox({
			'width'         : '0%',
			'height'        : '0%',				
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'type'			: 'iframe',	
			'href'			: "/<?php echo DIR_TES?>/enrolamiento/file_to_card/<?php echo $id?>",
			'onClosed'		: function(){
				window.location.href="/<?php echo DIR_TES?>/enrolamiento/";
			},
			onComplete      : function(){
				setTimeout(function(){jQuery.fancybox.close()},2000);
			}
		});
	}
	else window.location.href = "/<?php echo DIR_TES?>/enrolamiento/";
	<?php } ?>
    $('#paginador a').click(function(e){
        e.preventDefault();
        pag = $(this).attr('href');
        $('#form_filter_bitacora').attr('action', pag);
        $('#form_filter_bitacora').submit();
    });

    $('#btnFiltrar').click(function(e){
        // Eliminar la pagina de la url del action
        action = $('#form_filter_bitacora').attr('action');
        action = action.replace(/\d+(\/)*$/,'');

        $('#form_filter_bitacora').attr('action',action);
        $('#form_filter_bitacora').submit();
    });
	
    $("a#detalles").fancybox({
		'width'             : '90%',
		'height'            : '100%',				
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'type'			: 'iframe',								
		onComplete      : function(){
            $('#fancybox-frame').load(function(){
                $.fancybox.hideActivity();
            });
        }
	});
	$("a#detalles").click(function(e) {
        $.fancybox.showActivity();
    });
    
    $(".btnGrabarTarjeta").click(function(event){
        ruta = $(this).attr('href');

        $.get(ruta, function(datos) {
            tesNFC.write(datos);

            if(tesNFC.error) {
                alert("Error al guardar los datos del paciente, intentelo nuevamente");
            };
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert("Error al obtener los datos del paciente, intentelo nuevamente.");
            console.log("status: "+textStatus+", statusText: "+errorThrown);
        });

        event.stopPropagation();
        event.preventDefault();
    });
});
</script>
<?php 
$opcion_insert = Menubuilder::isGranted(DIR_TES.'::enrolamiento::insert');
$opcion_view = Menubuilder::isGranted(DIR_TES.'::enrolamiento::view');
$opcion_update = Menubuilder::isGranted(DIR_TES.'::enrolamiento::update');
$opcion_print = Menubuilder::isGranted(DIR_TES.'::enrolamiento::file_to_card');
?>
<?php 
if(!empty($msgResult))
	echo "<div class='".$infoclass."'>".$msgResult."</div>";
?>
<div class="input-append"><h2><?php echo $title ?></h2>

<?php echo form_open(DIR_TES.'/enrolamiento/index/'.$pag, array('name'=>'form_filter_bitacora', 'id'=>'form_filter_bitacora')); ?>
Buscar usuario
<input type="text" name="busqueda" value="<?php echo set_value('busqueda', ''); ?>" class="spa10" placeholder="Buscar Paciente (curp ó nombre)" style="width:90%" /> 
<button type="submit" name="btnFiltrar" id="btnFiltrar"   class="btn btn-primary">Buscar <i class="icon-search"></i></button>
<?php if($opcion_insert) { ?><a href="/<?php echo DIR_TES?>/enrolamiento/insert" class="btn btn-primary" style="margin-left:5px">Crear nuevo <i class="icon-plus"></i></a><?php } ?>
</form>
</div>
<div class="table table-striped  " >
<table width="100%">
<thead>
	<tr>
		<th><h2>CURP</h2></th>
		<th><h2>Nombre completo</h2></th>
        <th><h2>Edad</h2></th>
		<?php if($opcion_view) { ?><th></th><?php } ?>
		<?php if($opcion_update) { ?><th></th><?php } ?>
        <?php if($opcion_print) { ?><th></th><?php } ?>
        <!-- <th></th> -->
	</tr>
    </thead>
	<?php if (isset($users)) foreach ($users as $user_item): ?>
	<tr >
		<td><?php echo $user_item->curp ?></td>
		<td><?php echo $user_item->nombre.' '.$user_item->apellido_paterno.' '.$user_item->apellido_materno ?></td>
        <td><?php 
				$datetime1 = date_create($user_item->fecha_nacimiento);
				$datetime2 = date_create(date("Y-m-d"));
				$interval  = date_diff($datetime1, $datetime2);
				echo "A:".$interval->format('%y')." M:".$interval->format('%m')." D:".$interval->format('%d');
			?></td>
		<?php if($opcion_view) { ?><td><a href="/<?php echo DIR_TES?>/enrolamiento/view/<?php echo $user_item->id ?>" class="btn btn-small btn-primary btn-icon" id="detalles">Detalles<i class="icon-eye-open"></i></a></td><?php } ?>
		<?php if($opcion_update) { ?><td><a href="/<?php echo DIR_TES?>/enrolamiento/update/<?php echo $user_item->id ?>" class="btn btn-small btn-primary btn-icon">Modificar<i class="icon-pencil"></i></a></td><?php } ?>
        <?php if($opcion_print) { ?><td><a href="/<?php echo DIR_TES?>/enrolamiento/file_to_card/<?php echo $user_item->id ?>" class="btn btn-small btn-primary btn-icon" target="_blank">Descargar<i class="icon-download-alt"></i></a></td><?php } ?>
        <!-- <td><a href="/<?php echo DIR_TES?>/enrolamiento/file_to_card/<?php echo $user_item->id ?>/false" class="btn btn-small btn-primary btn-icon btnGrabarTarjeta" style="width: 95px;">Grabar tarjeta<i class="icon-hdd"></i></a></td> -->
	</tr>
	<?php endforeach ?>
    <tfoot>
        <tr><td colspan="7">
            <div id="paginador" align="center"><?php echo $this->pagination->create_links(); ?></div>
        </td></tr>
    </tfoot>
</table>
</div>
<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>

