    <link href="/resources/css/grid.css" rel="stylesheet" type="text/css" /> 
    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="/resources/js/enrolamiento.js"></script>
<?php 
if($enrolado)
{
$cn_basico = Menubuilder::isGranted(DIR_TES.'::enrolamiento::basico_edit');
$cn_beneficiario = Menubuilder::isGranted(DIR_TES.'::enrolamiento::beneficiario_edit');
$cn_tutor = Menubuilder::isGranted(DIR_TES.'::enrolamiento::tutor_edit');
$cn_umt = Menubuilder::isGranted(DIR_TES.'::enrolamiento::umt_edit');
$cn_regcivil = Menubuilder::isGranted(DIR_TES.'::enrolamiento::regcivil_edit');
$cn_direccion = Menubuilder::isGranted(DIR_TES.'::enrolamiento::direccion_edit');

$cn_alergia = Menubuilder::isGranted(DIR_TES.'::enrolamiento::alergia_edit');
$cn_vacuna = Menubuilder::isGranted(DIR_TES.'::enrolamiento::vacuna_edit');
$cn_ira = Menubuilder::isGranted(DIR_TES.'::enrolamiento::ira_edit');
$cn_eda = Menubuilder::isGranted(DIR_TES.'::enrolamiento::eda_edit');
$cn_consulta = Menubuilder::isGranted(DIR_TES.'::enrolamiento::consulta_edit');
$cn_accion = Menubuilder::isGranted(DIR_TES.'::enrolamiento::accion_edit');
$cn_nutricion = Menubuilder::isGranted(DIR_TES.'::enrolamiento::nutricion_edit');
?>    
    <script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    
    <link href="/resources/themes/jquery.ui.all.css" rel="stylesheet" type="text/css" />
    <style>
	td p
	{
		font-family:Open Sans Condensed ,sans-serif; font-size: 18px; font-weight: bold; text-shadow: 0 0px 0 #FFFFFF;
	}
	</style>
   
    <script>
	$(document).ready(function()
	{
		$.fancybox.showActivity();
		$("#fecha_edo").click(function(e) {
            if(this.checked)
				add_fecha_edo();
			else
				rem_fecha_edo();
        });
		$("#localidadT,#ageb").change(function(e) {
			obtener_um_responsabilidad();
        });
		$("#nombre,#paterno,#materno,#lnacimientoT,#curp,#curp2,#fnacimiento,#curpT,#calle,#referencia,#colonia").change(function(e) {
            comparar_captura();
        });
		$("#ageb").click(function(e) {
			obtener_um_responsabilidad();
        });
		$("#ageb").blur(function(e) {
			obtener_um_responsabilidad();
        });
		$("#ageb").autocomplete({
				source: function(request, response) {
                $.ajax({
                  url: "/<?php echo DIR_TES?>/enrolamiento/searchageb/"+$('#localidad').val()+"/"+request.term,
                  dataType: "json",
                  success: function(data) {
                    response(data);
                  }
                });}
		})
		<?php if($cn_tutor) {?>
		$("#buscar").autocomplete({
				source: "/<?php echo DIR_TES?>/enrolamiento/autocomplete/",
				select: function (a, b) 
				{
					var valor=b.item.value;
					buscarTutor(valor.substr(0,valor.indexOf(" ")));
				}
		})
		<?php }?>
		$("#fnacimiento").datepicker(optionsFecha );
		$("#fechacivil").datepicker();
		$("a#fba1").fancybox({
			'width'             : '50%',
			'height'            : '60%',				
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'type'			: 'iframe',				
			'onClosed'		: function() {
				var  uri=this.href; 
				uri=uri.substr(uri.search("0")+2,uri.length);
				uri=uri.substr(0,uri.search("1")-1);
				var array=document.getElementById(uri.substr(0,uri.search("/"))).value;
				if(array!="")
				{
					var des=5;
					if(uri.substr(uri.search("/")+1,uri.length)=="umt")des=5;
					$.ajax({
					type: "POST",
					data: {
						'claves':[array] ,
						'desglose':des },
					url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
					})
					.done(function(dato)
					{
						if(dato)
						{
							var obj = jQuery.parseJSON( dato );
							var des=obj[0]["descripcion"];
							var ed=des.split(",");
							ed=ed[ed.length-2];
							des=des.replace(ed+",", "");
							document.getElementById(uri.substr(uri.search("/")+1,uri.length)).value=des;
							if(uri.substr(uri.search("/")+1,uri.length)=="umt")
							{
								$.get('/<?php echo DIR_TES.'/enrolamiento/validarisum/';?>'+document.getElementById("um").value, function(respuesta) 
								{
									if(respuesta=="no")
									{
										alert("El nombre seleccionado no es una unidad medica \nPara continuar seleccione una unidad medica valida");
										document.getElementById("um").value="";
										document.getElementById("umt").value="";
									}
     							});
							}
							if(uri.substr(uri.search("/")+1,uri.length)=="localidadT")
								obtener_um_responsabilidad();
						}
						if(uri.substr(uri.search("/")+1,uri.length)=="lnacimientoT")
						getcurp();
					});
				}
			},
			onComplete: function(){
            $('#fancybox-frame').load(function(){
                $.fancybox.hideActivity();
            });
        }
	});
	$("a#fba1").click(function(e) {
        $.fancybox.showActivity();
    });						
	
		<?php if($cn_basico) {?> 
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_asu_localidad_nacimiento;?>] ,
			'desglose':5 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("lnacimiento").value=obj[0]["id"];
				document.getElementById("lnacimientoT").value=des;
			}
		});
		<?php }?>
		
		<?php if($cn_umt) {?>
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_asu_um_tratante;?>] ,
			'desglose':5 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("um").value=obj[0]["id"];
				document.getElementById("umt").value=des;
			}
		});
		<?php }?>
		<?php if($cn_regcivil) {?>
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_localidad_registro_civil;?>] ,
			'desglose':5 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("lugarcivil").value=obj[0]["id"];
				document.getElementById("lugarcivilT").value=des;
			}
		});
		<?php }?>
		<?php if($cn_direccion) {?>
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_asu_localidad_domicilio;?>] ,
			'desglose':5 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				var des=obj[0]["descripcion"];
				var ed=des.split(",");
				ed=ed[ed.length-2];
				des=des.replace(ed+",", "");
				
				document.getElementById("localidad").value=obj[0]["id"];
				document.getElementById("localidadT").value=des;
			}
		});
		<?php
		}
		$alerg="";
		$afili="";
		foreach ($alergias as $alergia)
		{
			$alerg.=$alergia->id."_";
		}
		foreach ($afiliaciones as $afiliacion)
		{
			$afili.=$afiliacion->id."_";
		}
		?>
		$("#alergias").load("/tes/enrolamiento/catalog_check/alergia/checkbox/3/<?php echo $alerg;?>/tipo/tipo");	
		$("#tbenef").load("/tes/enrolamiento/catalog_check/afiliacion/checkbox/2/<?php echo $afili;?>");		
		$("#sangre").load("/tes/enrolamiento/catalog_select/tipo_sanguineo/<?php echo $enrolado->sangre; ?>");	
		$("#nacionalidad").load("/tes/enrolamiento/catalog_select/nacionalidad/<?php echo $enrolado->nacionalidadid; ?>/descripcion");
		$("#compania").load("/tes/enrolamiento/catalog_select/operadora_celular/<?php echo $enrolado->operadoraid; ?>");
		
		$("#companiaT").load("/tes/enrolamiento/catalog_select/operadora_celular/<?php echo $enrolado->operadoraTid; ?>", function() {
			$("#guardar").attr("disabled",false);	
			$.fancybox.hideActivity();	
		});
		
		$("#nombre,#paterno,#materno,#fnacimiento,#lnaciminetoT").blur(function()
		{       
			getcurp();
		});	
		<?php if($cn_tutor) {?>
		$("#captura").click(function(e) {
            habilitarTutor();
        });
		$("#buscarCurp").click(function(e) {
            buscarTutor($("#buscar").val().substr(0,$("#buscar").val().indexOf(" ")));
			return false;
        });
		$("#curpT").blur(function(e) {
            buscarTutor(this.value);
        });
		
		habilitarTutor();
		<?php }?>
	});	
	</script><!-- mensaje-->
        <?php 	
			if(!empty($msgResult))
			echo "<div class='$infoclass'>".$msgResult."</div>";
			echo validation_errors(); 
			echo form_open(DIR_TES.'/enrolamiento/update/'.$enrolado->id,array('onkeyup' => 'cleanForm()','onclick' => 'cleanForm()', 'id' => 'enrolar')); 
		?>
      <!-- mensaje -->
      <div class="info requiere" style="width:93.2%"><img src="/resources/images/asterisco.png" />Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert" style="width:93.2%"></div>
	<table align="center" width="97.5%" border="0" cellpadding="0" cellspacing="0" style="margin-left:20px"><tr><td>
    	
        	<table width="100%">
            <tr>
                <td>
                  <div id="Accordion1" class="Accordion" tabindex="0" style="margin-left:-20px;" >
                  
                  <!-- Datos basicos -->
                  <?php if($cn_basico){ ?>
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos Basicos</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%" height="50"><p align="right">Nombre</p></td>
                            <td width="31%"><input name="nombre" type="text" title='requiere' required id="nombre" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->nombre; ?>" maxlength="35">
                            <input name="id" type="hidden" id="id" value="<?php echo $id;?>"  />
                            <input name="id_cns_basico" type="hidden" id="id_cns_basico" value="<?php echo $id;?>"  /></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%">
                            
                              <label style=" margin-left:10px; float:left">
                                <input type="radio" name="sexo" value="M" <?php if($enrolado->sexo=="M") echo "checked"; ?> id="sexo_1" onclick="getcurp();" title='requiere' required style="margin-top:-3px;">
                                Masculino</label>
                              <label style=" float:left">
                                <input type="radio" name="sexo" value="F" <?php if($enrolado->sexo=="F") echo "checked"; ?> id="sexo_2" onclick="getcurp();" style="margin-top:-3px;">
                                Femenino</label>
                             </td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><input name="paterno" type="text" title='requiere' required id="paterno" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->apellido_paterno; ?>" maxlength="20"></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td>
                              <select name="sangre" id="sangre" style="width:80%; margin-left:10px;" title='requiere' required>                           
                            
                            </select></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><input name="materno" type="text" title='requiere' required id="materno" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->apellido_materno; ?>" maxlength="20"></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><input name="fnacimiento" type="text" id="fnacimiento" style="width:65%; margin-left:10px;" title='requiere' required value="<?php echo date('d-m-Y', strtotime($enrolado->fecha_nacimiento)); ?>" placeholder="dd-mm-yyyy"></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3">
                            <div class="input-append" style="width:100%"><input name="lnacimientoT" type="text" title='requiere' required id="lnacimientoT" style="width:68%; margin-left:10px;" value="" readonly="readonly" >
                            	<input name="lnacimiento" type="hidden" id="lnacimiento" value="">                              
                              <a href='/<?php echo DIR_TES?>/tree/create/TES/Lugar de Nacimiento/1/radio/0/lnacimiento/lnacimientoT/1/1/<?php echo urlencode(json_encode(array(2,5)));?>/<?php echo urlencode(json_encode(array(2,3,4)));?>' id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a><div id="aqui"></div>
                              </div>
                              </td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td ><input name="curp" type="text" id="curp"  style="letter-spacing:1px; width:50%;margin-left:10px;" onkeypress="return validar(event,'NL',this.id)" value="<?php echo substr($enrolado->curp,0,12); ?>" maxlength="12">
                            <input name="curp2" type="text"  id="curp2"  style="letter-spacing:1px; width:22.5%" onkeypress="return validar(event,'NL',this.id)" value="<?php echo substr($enrolado->curp,12,15); ?>" maxlength="6"></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><select name="nacionalidad" id="nacionalidad" style="width:80%; margin-left:10px;" title='requiere' required="title='requiere' required">
                            </select></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td colspan="3" ><span id="nocurp" style="letter-spacing:1px; width:100%;margin-left:10px;"></span></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php } else { ?>
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos Basicos</div>
                      <div class="AccordionPanelContent" >
                      	<input name="id" type="hidden" id="id" value="<?php echo $id;?>"  />
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nombre;?></div></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%"><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->sexo;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->apellido_paterno;?></div></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->tsangre;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->apellido_materno;?></div></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->fecha_nacimiento;?></div></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><div id="lnacimientoT" style="width:100%; margin-left:20px;"></div></td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td ><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->curp;?></div></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><div style="width:100%; margin-left:20px; margin-top:-5px"><?php  echo $enrolado->nacionalidad;?></div></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- Tutor -->
                  	<?php if($cn_tutor){ ?>
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos de la Madre o Tutor</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td colspan="2" height="50"><p align="right">Madres o Tutores ya Capturados</p></td>
                            <td colspan="2"><div class="input-append">
                              <input name="buscar" type="text" id="buscar" style="width:100%; margin-left:10px;" value="<?php echo set_value('buscar', '') ?>" class="spa10" placeholder="Buscar"/>
                              <a href="#" id="buscarCurp" class="btn btn-primary">Buscar <i class="icon-search"></i></a>
                              <input name="id_cns_tutor" type="hidden" id="id_cns_tutor" value="<?php echo $id;?>"  />
                              
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2"><p align="right" id="tutoredit">Capturar Nueva Madre o Tutor</p>                              
                            <label for="captura"></label></td>
                            <td colspan="2" align="left">
                              <input type="checkbox" name="captura" id="captura" style="margin-left:10px; margin-top:-10px;" value="1"  />
                              <input name="idtutor" type="hidden" id="idtutor"  />
                              &nbsp;
                              <span id="buscarError" style="color:#F00"></span>
                            </td>
                          </tr>
                          <tr>
                            <td width="19%"><p align="right">CURP</p></td>
                            <td width="31%"><input name="curpT" type="text" id="curpT" style="width:80%; margin-left:10px;"  value="<?php echo $enrolado->curpT; ?>" maxlength="18" onkeypress="return validar(event,'NL',this.id)"/></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%">
                              <label style=" margin-left:10px; float:left">
                                <input type="radio" name="sexoT" value="M" <?php if( $enrolado->sexoT=="M") echo "checked"; ?> id="sexoT_1" style="margin-top:-3px;">
                                Masculino</label>
                              <label style=" float:left">
                                <input type="radio" name="sexoT" value="F" <?php if( $enrolado->sexoT=="F") echo "checked"; ?> id="sexoT_2" style="margin-top:-3px;">
                                Femenino</label>
                             </td>
                          </tr>
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><input name="nombreT" type="text" title='requiere' required="title='requiere' required" id="nombreT" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php  echo set_value('nombreT', $enrolado->nombreT) ; ?>" maxlength="35" readonly="readonly"/></td>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><input name="telefonoT" type="text" id="telefonoT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->telefonoT; ?>" readonly="readonly" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><input name="paternoT" type="text" title='requiere' required="title='requiere' required" id="paternoT" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->paternoT; ?>" maxlength="20" readonly="readonly" /></td>
                            <td><p align="right">Celular</p></td>
                            <td><input name="celularT" type="text" id="celularT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->celularT; ?>" readonly="readonly" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><input name="maternoT" type="text" title='requiere' required="title='requiere' required" id="maternoT" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->maternoT; ?>" maxlength="20" readonly="readonly" /></td>
                            <td><p align="right">Compania Celular</p></td>
                            <td><select name="companiaT" id="companiaT" style="width:85%; margin-left:10px;" >
                            </select></td>
                          </tr>
                           <tr>
                            <td>&nbsp;</td>
                            <td colspan="3"><label><input type="checkbox" name="fecha_edo" id="fecha_edo" style="margin-left:10px; margin-top:-3px;" />
                            No tiene la curp pero sabe su fecha y estado de nacimiento </label>
                            <div id="tutorcurp"></div>
                            <div id="errorcurptutor"></div>
                            </td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- Direccion  -->
                    <?php if($cn_direccion){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Domicilio</div>
                      <div class="AccordionPanelContent">
                      <div id="compartetutor" style="width:94.7%" > </div>
                        <div id="ladireccion">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%" height="50"><p align="right">Calle</p></td>
                            <td width="31%"><input name="calle" type="text" id="calle" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->calle_domicilio; ?>"></td>
                            <td width="25%"><p align="right">
                              <input name="id_cns_direccion" type="hidden" id="id_cns_direccion" value="<?php echo $id;?>"  />
                            Número</p></td>
                            <td width="25%"><input name="numero" type="text" id="numero" style="width:75%; margin-left:10px;" value="<?php echo $enrolado->numero_domicilio; ?>"></td>
                          </tr>
                          <tr>
                            <td><p align="right">Referencia</p></td>
                            <td colspan="3"><input name="referencia" type="text" id="referencia" style="width:68%; margin-left:10px;"  value="<?php echo $enrolado->referencia_domicilio; ?>" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Colonia</p></td>
                            <td><input name="colonia" type="text" id="colonia" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->colonia_domicilio; ?>"></td>
                            <td><p align="right">CP</p></td>
                            <td><input name="cp" type="text" id="cp" style="width:75%; margin-left:10px;" value="<?php echo $enrolado->cp_domicilio; ?>" maxlength="5" ></td>
                          </tr>
                          
                          <tr>
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><div class="input-append" style="width:100%"><input name="localidadT" type="text" title='requiere' required="title='requiere' required" id="localidadT" style="width:68%; margin-left:10px;" value="" readonly="readonly">
                              <input name="localidad" type="hidden" id="localidad" value=""/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Direccion/1/radio/0/localidad/localidadT/1/1/<?php echo urlencode(json_encode(array(2,5)));?>/<?php echo urlencode(json_encode(array(2,3,4)));?>" id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a></div>
                          </tr>
                          <tr>
                          <td colspan="4" width="100%">
                              <table width="97%" border="0">
                                <tr>
                                  <td width="19%" align="right"><p>Ageb</p></td>
                                  <td ><input name="ageb" type="text"  id="ageb" style="width:75%; margin-left:15px;" value="<?php echo $enrolado->ageb; ?>" maxlength="4" onkeypress="return validar(event,'NL',this.id)" /></td>
                                  <td  align="right"><p>Sector</p></td>
                                  <td ><input name="sector" type="text"  id="sector" style="width:75%; margin-left:10px;" value="<?php echo $enrolado->sector; ?>" maxlength="4" onkeypress="return validar(event,'NL',this.id)"/></td>
                                  <td  align="right"><p>Manzana</p></td>
                                  <td ><input name="manzana" type="text"  style="width:75%; margin-left:10px;" value="<?php echo $enrolado->manzana; ?>" maxlength="3" onkeypress="return validar(event,'NL',this.id)"/></td>
                                </tr>
                              </table>
                          </td>
                          </tr>
                          <tr>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><input name="telefono" type="text" id="telefono" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->telefono_domicilio; ?>" /></td> 
                            <td><p align="right">Celular</p></td> 
                            <td><input name="celular" type="text" id="celular" style="width:75%; margin-left:10px;" value="<?php echo $enrolado->celular; ?>" /></td>                          
                          </tr>
                          <tr>
                            <td><p align="right">Compania Celular</p></td>
                            <td><select name="compania" id="compania" style="width:85%; margin-left:10px;" >
                            </select></td> 
                            <td></td> 
                            <td></td>                          
                          </tr>
                        </table>
                        </div>
                        <br />
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- Tipo de Beneficiario:  -->
                    <?php if($cn_beneficiario){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Derechohabiencia</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                       	<div id="tbenef" style="margin-left:10px;">
                            
                            </div>
                            <input name="id_cns_beneficiario" type="hidden" id="id_cns_beneficiario" value="<?php echo $id;?>"  />
                      	</div>
                      </div>
                    </div>
                    <?php }?>
                    
                    
                    
                    <!--  Unidad Medica Tratante -->
                    <?php if($cn_umt){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Unidad Medica de Responsabilidad</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                      
                          <tr>
                            <td width="19%" height="50"><p align="right">
                              <input name="id_cns_umt" type="hidden" id="id_cns_umt" value="<?php echo $id;?>"  />
                            Lugar</p></td>
                            <td width="81%" colspan="3">
                            <span style="font-size:12px; margin-left:10px; font-style:italic;">um, localidad ,municipio, estado</span><div class="input-append" style="width:100%"><input name="umt" type="text" id="umt" style="width:68%; margin-left:10px;"  value="<?php echo set_value('lugarcivilT', ''); ?>" readonly="readonly">
                              <input name="um" type="hidden" id="um"  value="<?php echo set_value('um', ''); ?>"/>
                              </div>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php }?>
                    
                    <!--  Registro civil -->
                    <?php if($cn_regcivil){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Registro Civil</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%" height="50"><p align="right">Fecha</p></td>
                            <td width="31%"><input name="fechacivil" type="text" id="fechacivil" style="width:75%; margin-left:10px;"  value="<?php echo date("d-m-Y",strtotime($enrolado->fecha_registro)); ?>" placeholder="dd-mm-yyyy"></td>
                            <td width="25%"><p align="right">
                              <input name="id_cns_regcivil" type="hidden" id="id_cns_regcivil" value="<?php echo $id;?>"  />
                            </p></td>
                            <td width="25%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar</p></td>
                            <td colspan="3"><div class="input-append" style="width:100%"><input name="lugarcivilT" type="text" id="lugarcivilT" style="width:68%; margin-left:10px;"  value="" readonly="readonly" >
                              <input name="lugarcivil" type="hidden" id="lugarcivil"  value=""/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Registro Civil/1/radio/0/lugarcivil/lugarcivilT/1/1/<?php echo urlencode(json_encode(array(2,5)));?>/<?php echo urlencode(json_encode(array(2,3,4)));?>" id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a></div>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php }?>
                    
                    
                    
                    <!-- alergias y reacciones:  -->
                    <?php if($cn_alergia){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Alergias y Antecedentes Familiares de Riesgo</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        	<div id="alergias" style="margin-left:10px;">
                            
                            </div>
                            <input name="id_cns_alergia" type="hidden" id="id_cns_alergia" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    
                  <!-- vacunacion  -->
                    <?php if($cn_vacuna){ ?>
                  <div class="AccordionPanel">
                    <div class="AccordionPanelTab">Control de Vacunación</div>
                      <div class="AccordionPanelContent"><br />                      
                      	<div style="margin-left:20px; width:90%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">Vacuna</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($vacunas,'vacuna','vn');?>
                                  <div id="vc">
                                  </div>                           
                                 </td>
                              <td valign="top" > 
                                   <button type="button" class="btn btn-primary" onclick="add('vacuna','vn','vc');" style="height:40px; width:100px;">Agregar <i class="icon-plus"></i></button>
                                   <button type="button" class="btn btn-primary" onclick="rem('vacuna','vn');" style="height:40px; width:100px;">Quitar &nbsp;&nbsp;<i class="icon-remove"></i></button> </td>
                              </tr>                     
                          </table>
                        <input name="id_cns_vacuna" type="hidden" id="id_cns_vacuna" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- ira  -->
                    <?php if($cn_ira){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de IRA</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="28%" align="left">IRA</th>
                                        <th width="15%" align="left">Fecha</th>
                                        <th width="20%" align="left">Tipo</th>
                                        <th width="27%" align="left">Tratamiento</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($iras,'ira','in');?>
                                  <div id="ic">
                                  </div>                           
                                 </td>
                              <td valign="top"> 
                                   <button type="button" class="btn btn-primary" onclick="add('ira','in','ic');" style="height:40px; width:100px;">Agregar <i class="icon-plus"></i></button>
                                   <button type="button" class="btn btn-primary" onclick="rem('ira','in');" style="height:40px; width:100px;">Quitar &nbsp;&nbsp;<i class="icon-remove"></i></button> </td>
                              </tr>                     
                          </table>
                        <input name="id_cns_ira" type="hidden" id="id_cns_ira" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- eda  -->
                    <?php if($cn_eda){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de EDA</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="28%" align="left">EDA</th>
                                        <th width="15%" align="left">Fecha</th>
                                        <th width="20%" align="left">Tipo</th>
                                        <th width="27%" align="left">Tratamiento</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($edas,'eda','en');?>
                                  <div id="ec">
                                  </div>                           
                                 </td>
                                 <td valign="top"> 
                                   <button type="button" class="btn btn-primary" onclick="add('eda','en','ec');" style="height:40px; width:100px;">Agregar <i class="icon-plus"></i></button>
                                   <button type="button" class="btn btn-primary" onclick="rem('eda','en');" style="height:40px; width:100px;">Quitar &nbsp;&nbsp;<i class="icon-remove"></i></button> </td>
                              </tr>                     
                          </table>
                        <input name="id_cns_eda" type="hidden" id="id_cns_beneficiario2" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- consulta  -->
                    <?php if($cn_consulta){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Consulta</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="28%" align="left">Consulta</th>
                                        <th width="15%" align="left">Fecha</th>
                                        <th width="20%" align="left">Tipo</th>
                                        <th width="27%" align="left">Tratamiento</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($consultas,'consulta','ncc');?>
                                  <div id="ccc">
                                  </div>                           
                                 </td>
                                 <td valign="top"> 
                                   <button type="button" class="btn btn-primary" onclick="add('consulta','ncc','ccc');" style="height:40px; width:100px;">Agregar <i class="icon-plus"></i></button>
                                   <button type="button" class="btn btn-primary" onclick="rem('consulta','ncc');" style="height:40px; width:100px;">Quitar &nbsp;&nbsp;<i class="icon-remove"></i></button>   
                                   
                                  </td>
                              </tr>                     
                          </table>
                        <input name="id_cns_consulta" type="hidden" id="id_cns_consulta" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    
                    
                    
                  <!-- nutricion  -->
                    <?php if($cn_nutricion){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="18%" align="left">Peso (kg)</th>
                                        <th width="18%" align="left">Altura (cm)</th>
                                        <th width="18%" align="left">Talla cintura (cm)</th>
                                        <th width="36%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
  <?php
  $i=0; $grid="";
	foreach($nutriciones as $dato)
	{
		$i++;
		$dato=(array)$dato;
		$talla=$dato["talla"];
		$altura=$dato["altura"];
		$peso=$dato["peso"];
		$fecha=$dato["fecha"];
		$clase="row2";
		if($i%2)$clase="row1";
		$num=$i;
		if($i<10)$num="0".$i;
		$grid.= '<span id="r'."CNu".$num.'" ><div class="'.$clase.'" >
				<table width="100%" >
				<tr>
					<th width="10%" >'.$num.'</th>
					<th width="18%" align="left"><input type="number" step=".01" min="0" name="cpeso[]" id="cpeso'.$num.'"  style="width:85%;" value="'.$peso.'"></th> 
					<th width="18%"><input type="number" step=".01" min="0" max="3000" name="caltura[]" id="caltura'.$num.'" style="width:85%;" value="'.$altura.'"></th>  
					<th width="18%"><input type="number" step=".01" min="0" name="ctalla[]" id="ctalla'.$num.'"  style="width:85%;" value="'.$talla.'"></th>  
					<th width="36%"><input name="fCNu[]" type="text" id="fCNu'.$num.'" value="'.date("d-m-Y",strtotime($fecha)).'"></th>
				</tr>
				</table> 
			  </div></span>
			  <script>
			  $(document).ready(function()
				{
					$("#fCNu'.$num.'").datepicker();
				});</script>';
			  
		 
	 }
	
	$grid.='<input type="hidden" id="nNu" value="'.$i.'" />';
	echo $grid;
	?>
                                  <div id="cNu">
                                  </div>                           
                                 </td>
                                 <td valign="top"> 
                                   <button type="button" class="btn btn-primary" onclick="addNutricional();" style="height:40px; width:100px;">Agregar <i class="icon-plus"></i></button>
                                   <button type="button" class="btn btn-primary" onclick="remNutricional();" style="height:40px; width:100px;">Quitar &nbsp;&nbsp;<i class="icon-remove"></i></button> 
                                   
                                  </td>
                              </tr>                     
                          </table>
                        <input name="id_cns_nutricion" type="hidden" id="id_cns_nutricion" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                      <?php }?>
                    </div>                                        
                    
                    
                    <!-- accion nutricional  -->
                    <?php if($cn_accion){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Acción Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="width:100%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="50%" align="left">A. Nutriconal</th>
                                        <th width="40%" align="left">Fecha</th>
                                    </tr>
                                  </table> 
                                  </div>
                                  <?php echo getArray($nutricionales,'accion_nutricional','nac');?>
                                  <div id="can">
                                  </div>                           
                                 </td>
                                 <td valign="top"> 
                                   <button type="button" class="btn btn-primary" onclick="add('accion_nutricional','nac','can');" style="height:40px; width:100px;">Agregar <i class="icon-plus"></i></button>
                                   <button type="button" class="btn btn-primary" onclick="rem('accion_nutricional','nac');" style="height:40px; width:100px;">Quitar &nbsp;&nbsp;<i class="icon-remove"></i></button> </td>
                              </tr>                     
                          </table>
                        <input name="id_cns_accion" type="hidden" id="id_cns_accion" value="<?php echo $id;?>"  />
                        </div>
                      </div>
                    </div>
                    <?php }?>
                    </td>
            </tr>
            <tr>
                <td>
                <div id="tienesimilar" style="width:95.7%; margin-left:-20px; margin-bottom:10px;" > </div>
                <div id="tieneum" style="width:95.7%; margin-left:-20px; margin-bottom:10px;" ></div>
                <span id="enviandoof" style="margin-left:-20px;">
                <button class="btn btn-primary" type="submit" name="guardar" id="guardar" onclick="return validarFormulario('enrolar')" disabled="disabled">Guardar <i class="icon-hdd"></i></button>
                <button class="btn btn-primary" type="button" onclick="window.location.href='/<?php echo DIR_TES?>/enrolamiento/'" >Cancelar <i class="icon-arrow-left"></i></button>
                </span>
    			
                </td>
            </tr>
        </table>
	</td></tr></table>
<?php 
}
else
{
 echo "<div class='$infoclass'>".$msgResult."</div><div><br>";
 echo '<a href="" class="btn btn-primary" onclick="window.location.href=\'/'.DIR_TES.'/enrolamiento/\';return false;">Regresar</a></div>';
}

?><script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("Accordion1", { useFixedPanelHeights: false, defaultPanel: 0 });
</script>