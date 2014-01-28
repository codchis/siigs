    <link href="/resources/css/grid.css" rel="stylesheet" type="text/css" /> 
    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
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
	var g=new Date();
		var option = 
		{
			changeMonth: true,
			changeYear: true,
			duration:"fast",
			dateFormat: 'dd-mm-yy',
			constrainInput: true,
			firstDay: 1,
			closeText: 'X',
			showOn: 'both',
			buttonImage: '/resources/images/calendar.gif',
			buttonImageOnly: true,
			buttonText: 'Clic para seleccionar una fecha',
			yearRange: '1900:'+g.getFullYear(),
			showButtonPanel: false
		}

	$(document).ready(function()
	{
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
							document.getElementById(uri.substr(uri.search("/")+1,uri.length)).value=obj[0]["descripcion"];
							if(uri.substr(uri.search("/")+1,uri.length)=="umt")
							{
								$.get('/<?php echo DIR_TES.'/enrolamiento/validarisum/';?>'+document.getElementById("um").value, function(respuesta) 
								{console.log(respuesta);
									if(respuesta=="no")
									{
										alert("El nombre seleccionado no es una unidad medica \nPara continuar seleccione una unidad medica valida");
										document.getElementById("um").value="";
										document.getElementById("umt").value="";
									}
     							});
							}
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
			'desglose':1 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				document.getElementById("lnacimiento").value=obj[0]["id"];
				document.getElementById("lnacimientoT").value=obj[0]["descripcion"];
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
				document.getElementById("um").value=obj[0]["id"];
				document.getElementById("umt").value=obj[0]["descripcion"];
			}
		});
		<?php }?>
		<?php if($cn_regcivil) {?>
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_localidad_registro_civil;?>] ,
			'desglose':1 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				document.getElementById("lugarcivil").value=obj[0]["id"];
				document.getElementById("lugarcivilT").value=obj[0]["descripcion"];
			}
		});
		<?php }?>
		<?php if($cn_direccion) {?>
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_asu_localidad_domicilio;?>] ,
			'desglose':1 },
		url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				document.getElementById("localidad").value=obj[0]["id"];
				document.getElementById("localidadT").value=obj[0]["descripcion"];
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
		$("#companiaT").load("/tes/enrolamiento/catalog_select/operadora_celular/<?php echo $enrolado->operadoraTid; ?>");
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
	<?php if($cn_tutor) {?>
	function habilitarTutor()
	{
		if(document.getElementById("captura").checked)
		{
			$("#nombreT").removeAttr("readonly");
			$("#paternoT").removeAttr("readonly");
			$("#maternoT").removeAttr("readonly");
			$("#celularT").removeAttr("readonly");
			$("#telefonoT").removeAttr("readonly");
			$("#companiaT").removeAttr("readonly");
			$("#sexoT_1").removeAttr("readonly");
			$("#sexoT_2").removeAttr("readonly");
		}
		else
		{
			$("#nombreT").attr("readonly",true);
			$("#paternoT").attr("readonly",true);
			$("#maternoT").attr("readonly",true);
			$("#celularT").attr("readonly",true);
			$("#telefonoT").attr("readonly",true);
			$("#companiaT").attr("readonly",true);
			$("#sexoT_1").attr("readonly",true);	
			$("#sexoT_2").attr("readonly",true);
			var buscar=$("#curpT").val();
			if($("#buscar").val()!="")
				buscar=$("#buscar").val().substr(0,18);
			if(buscar!="")		
			buscarTutor(buscar);		
		}
	}
	function buscarTutor(buscar)
	{	
		buscar=buscar.replace(" ","");
		buscar=buscar.replace("=","");
		$("#idtutor").val("");
		$("#nombreT").val("");
		$("#paternoT").val("");
		$("#maternoT").val("");
		$("#celularT").val("");
		
		$("#telefonoT").val("");
		$("#companiaT").val("");
		$("#sexoT_1").attr("checked",false);
		$("#sexoT_2").attr("checked",false);
				
		if($("#buscar").val()!="")
		$("#buscarError").html('');
		//var buscar = $("#buscar").val();
		$.ajax({
			url: "/<?php echo DIR_TES?>/enrolamiento/data_tutor/"+buscar,
			type: "POST",
			data: "json",
			success:function(data){
				var obj = jQuery.parseJSON( data );
				//console.debug(obj);
				if(obj[0]["error"]=="")
				{
					$("#idtutor").val(obj[0]["idtutor"]);
					$("#nombreT").val(obj[0]["nombreT"]);
					$("#paternoT").val(obj[0]["paternoT"]);
					$("#maternoT").val(obj[0]["maternoT"]);
					$("#celularT").val(obj[0]["celularT"]);
					$("#curpT").val(obj[0]["curpT"]);
					$("#telefonoT").val(obj[0]["telefonoT"]);
					$("#companiaT option[value="+obj[0]["companiaT"]+"]").attr("selected",true);
					if(obj[0]["sexoT_1"]=="1")
					$("#sexoT_1").attr("checked",true);
					if(obj[0]["sexoT_2"]=="1")
					$("#sexoT_2").attr("checked",true);
					$("#tutoredit").html("Editar datos de la Madre o Tutor");
					$("#captura").attr("checked","true");
					$("#curpT").click();
				$.get('/<?php echo DIR_TES.'/enrolamiento/brothers_search/';?>'+$("#idtutor").val(), function(respuesta) 
					{
						if(respuesta.length>5)
						{
							var obj = jQuery.parseJSON( respuesta );
							var campo = '<span id="hermanos" >Hay personas con el mismo tutor: Si desea importar su misma dirección dele click<br>';
							for(var c=0;c<obj.length; c++)
								campo+='<input type="button"  value="'+obj[c]["nombre"]+'" onclick="importarDatos(\''+obj[c]["id_persona"]+'\')" style="padding:5px" class="btn btn-small btn-primary"/>&nbsp;&nbsp;'
							campo+='<br><input type="button"  value="LIMPIAR" onclick="limpiar_direccion()" style="padding:5px" class="btn btn-small btn-primary"/></span>';
							$("#compartetutor").append(campo);
							$("#compartetutor").attr("class","info");
						}
					});
				}
				else
				{
					$("#tutoredit").html("Capturar Nueva Madre o Tutor");
					$("#buscarError").html('<strong>'+obj[0]["error"]+'&nbsp;</strong>');
					if(document.getElementById("hermanos"))
					{
						$("#hermanos").remove();	
						$("#compartetutor").attr("class","");	
					}
				}
				habilitarTutor();
			}
		});
	}
	<?php }?>
	function limpiar_direccion()
	{
		$('#ladireccion').data('old-state', $('#ladireccion').html());
		$('#ladireccion').html($('#ladireccion').data('old-state'));
	}
	function importarDatos(id)
	{
		$.get('/<?php echo DIR_TES.'/enrolamiento/brother_found/';?>'+id, function(respuesta) 
		{
			if(respuesta.length>5)
			{
				var obj = jQuery.parseJSON( respuesta );
				$("#calle").val(obj[0]["calle_domicilio"]);
				$("#numero").val(obj[0]["numero_domicilio"]);
				$("#referencia").val(obj[0]["referencia_domicilio"]);
				$("#colonia").val(obj[0]["colonia_domicilio"]);
				$("#cp").val(obj[0]["cp_domicilio"]);
				$("#ageb").val(obj[0]["ageb"]);
				$("#sector").val(obj[0]["sector"]);
				$("#manzana").val(obj[0]["manzana"]);
				$("#localidad").val(obj[0]["id_asu_localidad_domicilio"]);
				$("#telefono").val(obj[0]["telefono_domicilio"]);
				$.ajax({
				type: "POST",
				data: {
					'claves':[$("#localidad").val()] ,
					'desglose':3 },
				url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
				})
				.done(function(dato)
				{
					if(dato)
					{
						var obj = jQuery.parseJSON( dato );
						document.getElementById("localidadT").value=obj[0]["descripcion"];
					}
				});
				$("#calle").click();
			}
		});
	}
	function omitirAcentos(text) 
	{
		var acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç";
		var original = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc";
		for (var i=0; i<acentos.length; i++) 
			text = text.replace(acentos.charAt(i), original.charAt(i));
		
		return text;
	}

	function getcurp()
	{
		var ap=omitirAcentos($("#paterno").val());
		var am=omitirAcentos($("#materno").val());
		var no=omitirAcentos($("#nombre").val());
		var se=$("input[name='sexo']:checked").val();
		var fn=$("#fnacimiento").val();
		var ed=$("#lnacimientoT").val().split(",");
		ed=ed[ed.length-1];
		ed=$.trim(ed);
		var d=fn.substr(0,2);
		var m=fn.substr(3,2);
		var a=fn.substr(6,4);
		var x=parseInt(a)+"";
		
		if(ap!=""&&am!=""&&no!=""&&se!=""&&fn!=""&&ed!="")
		{
			if(x.length>3)
			{
				$("#curp").val("");
				$("#curpl").html("");		
				$("#curp2").val("");
				$.ajax({
					url: "/<?php echo DIR_TES?>/obtenercurp/curp/"+ap+"/"+am+"/"+no+"/"+d+"/"+m+"/"+a+"/"+se+"/"+ed+"/2",
					type: "POST",
					data: "json",
					success:function(data){
						var obj = jQuery.parseJSON( data );
						if(data)
						{
							var curp=obj[0]["curp"];
							$("#curp").val(curp.substr(0,curp.length-5));
							$("#curpl").html('<strong>'+curp.substr(0,curp.length-5)+'&nbsp;</strong>');		
							$("#curp2").val(curp.substr(curp.length-5,5));		
						}
					}
				});
			}
			else {$("#fnacimiento").val("");$("#fnacimiento").attr("placeholder","dd-mm-yyyy"); $("#fnacimiento").focus();};
		}
	 	return false;
	}
	
	function add(id,n,a)
	{	
		num=document.getElementById(n).value*1;	
		num=num+1;
		document.getElementById(n).value=num;
		var miclase="";
		if((num%2)==0) miclase="row2"; else miclase="row1";
		if(num<10)num="0"+num;
		
		campo = '<span id="r'+id+num+'" ><div class="'+miclase+'" style=100%"><table width="100%" >  <tr>   <th width="10%">'+num+'</th>  <th width="50%"><select name="'+id+'[]" id="'+id+num+'" class="requiere" title="requiere" required style="width:98%;"></select></th>  <th width="40%"><input name="f'+id+'[]" type="text" id="f'+id+num+'" ></th> </tr> </table> </div></span>';
		$("#"+a).append(campo);
		$("#f"+id+num).val($.datepicker.formatDate('dd-mm-yy', new Date()));
		$("#f"+id+num).datepicker();
		$("#"+id+num).load("/tes/enrolamiento/catalog_select/"+id);
	}
	function rem(id,n)
	{
		num=document.getElementById(n).value;
		
		if(num != 0&&num>0)
		{
			if(num<10)num="0"+num;
			$("#r"+id+num).remove();
			num--;
			document.getElementById(n).value = num;
		}
	}
	
	function addNutricional()
	{	
		num=document.getElementById("nNu").value*1;	
		num=num+1;
		document.getElementById("nNu").value=num;
		var miclase="";
		if((num%2)==0) miclase="row2"; else miclase="row1";
		if(num<10)num="0"+num;
		
		campo = '<span id="r'+"CNu"+num+'" ><div class="'+miclase+'" style="100%"><table width="100%" >  <tr>   <th width="10%">'+num+'</th>  <th width="18%"><input type="number" step=".01" min="0" name="cpeso[]" id="cpeso'+num+'" style="width:85%;"></th> <th width="18%"><input type="number" step=".01" min="0" max="3000" name="caltura[]" id="caltura'+num+'" style="width:85%;"></th>  <th width="18%"><input type="number" step=".01" min="0" name="ctalla[]" id="ctalla'+num+'" style="width:85%;"></th>  <th width="36%"><input name="fCNu[]" type="text" id="fCNu'+num+'" ></th> </tr> </table> </div></span>';
		$("#cNu").append(campo);
		$("#fCNu"+num).val($.datepicker.formatDate('dd-mm-yy', new Date()));
		$("#fCNu"+num).datepicker();
	}
	function remNutricional()
	{
		num=document.getElementById("nNu").value;
		
		if(num != 0&&num>0)
		{
			if(num<10)num="0"+num;
			$("#rCNu"+num).remove();
			num--;
			document.getElementById("nNu").value = num;
		}
	}
	</script><!-- mensaje-->
        <?php 	
			if(!empty($msgResult))
			echo "<div class='$infoclass'>".$msgResult."</div>";
			echo validation_errors(); 
			echo form_open(DIR_TES.'/enrolamiento/update/'.$enrolado->id,array('onkeyup' => 'limpiaformulario(\'enrolar\')','onclick' => 'limpiaformulario(\'enrolar\')', 'id' => 'enrolar')); 
		?>
      <!-- mensaje -->
      <div class="info requiere" style="width:93%"><img src="/resources/images/asterisco.png" />Las formas y los campos marcados con un asterisco (<img src="/resources/images/asterisco.png" />) son campos obligatorios y deben ser llenados.</div>
    <div id="alert"></div>
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
                                <input type="radio" name="sexo" value="M" <?php if($enrolado->sexo=="M") echo "checked"; ?> id="sexo_1" onclick="getcurp();" title='requiere' required >
                                Masculino</label>
                              <label style=" float:left">
                                <input type="radio" name="sexo" value="F" <?php if($enrolado->sexo=="F") echo "checked"; ?> id="sexo_2" onclick="getcurp();">
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
                              <a href='/<?php echo DIR_TES?>/tree/create/TES/Lugar de Nacimiento/1/radio/0/lnacimiento/lnacimientoT/1/1/<?php echo urlencode(json_encode(array(2,4,5)));?>/<?php echo urlencode(json_encode(array(2,3)));?>' id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a><div id="aqui"></div>
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
                    
                    <!-- Tipo de Beneficiario:  -->
                    <?php if($cn_beneficiario){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Tipo de Beneficiario</div>
                      <div class="AccordionPanelContent"><br />
                      	<div style="margin-left:20px; width:90%">
                       	<div id="tbenef" style="margin-left:10px;">
                            
                            </div>
                            <input name="id_cns_beneficiario" type="hidden" id="id_cns_beneficiario" value="<?php echo $id;?>"  />
                      	</div>
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
                              <input type="checkbox" name="captura" id="captura" style="margin-left:10px;" value="1"  />
                              <input name="idtutor" type="hidden" id="idtutor"  />
                              &nbsp;
                              <span id="buscarError" style="color:#F00"></span>
                            </td>
                          </tr>
                          <tr>
                            <td width="19%"><p align="right">CURP</p></td>
                            <td width="31%"><input name="curpT" type="text" title='requiere' required id="curpT" style="width:80%; margin-left:10px;"  value="<?php echo $enrolado->curpT; ?>" maxlength="18" onkeypress="return validar(event,'NL',this.id)"/></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%">
                              <label style=" margin-left:10px; float:left">
                                <input type="radio" name="sexoT" value="M" <?php if( $enrolado->sexoT=="M") echo "checked"; ?> id="sexoT_1" >
                                Masculino</label>
                              <label style=" float:left">
                                <input type="radio" name="sexoT" value="F" <?php if( $enrolado->sexoT=="F") echo "checked"; ?> id="sexoT_2"  >
                                Femenino</label>
                             </td>
                          </tr>
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><input name="nombreT" type="text" title='requiere' required="title='requiere' required" id="nombreT" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php  echo set_value('nombreT', $enrolado->nombreT) ; ?>" maxlength="35" readonly="readonly"/></td>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><input name="celularT" type="text" id="celularT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->celularT; ?>" readonly="readonly" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><input name="paternoT" type="text" title='requiere' required="title='requiere' required" id="paternoT" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->paternoT; ?>" maxlength="20" readonly="readonly" /></td>
                            <td><p align="right">Celular</p></td>
                            <td><input name="telefonoT" type="text" id="telefonoT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->telefonoT; ?>" readonly="readonly" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><input name="maternoT" type="text" title='requiere' required="title='requiere' required" id="maternoT" style="width:80%; margin-left:10px;" onkeypress="return validar(event,'L',this.id)" value="<?php echo $enrolado->maternoT; ?>" maxlength="20" readonly="readonly" /></td>
                            <td><p align="right">Compania Celular</p></td>
                            <td><select name="companiaT" id="companiaT" style="width:85%; margin-left:10px;" >
                            </select></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php }?>
                    
                    <!--  Unidad Medica Tratante -->
                    <?php if($cn_umt){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Unidad Medica Tratante</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                      
                          <tr>
                            <td width="19%" height="50"><p align="right">
                              <input name="id_cns_umt" type="hidden" id="id_cns_umt" value="<?php echo $id;?>"  />
                            Lugar</p></td>
                            <td width="81%" colspan="3"><div class="input-append" style="width:100%"><input name="umt" type="text" id="umt" style="width:68%; margin-left:10px;"  value="<?php echo set_value('lugarcivilT', ''); ?>" readonly="readonly">
                              <input name="um" type="hidden" id="um"  value="<?php echo set_value('um', ''); ?>"/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Unidad Medica/1/radio/0/um/umt/1/1/<?php echo urlencode(json_encode(array(NULL)));?>/<?php echo urlencode(json_encode(array(5)));?>" id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a></div>
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
                            <td colspan="3"><div class="input-append" style="width:100%"><input name="lugarcivilT" type="text" id="lugarcivilT" style="width:68%; margin-left:10px;"  value="" readonly="readonly" title="requiere">
                              <input name="lugarcivil" type="hidden" id="lugarcivil"  value=""/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Registro Civil/1/radio/0/lugarcivil/lugarcivilT/1/1/<?php echo urlencode(json_encode(array(2,4,5)));?>/<?php echo urlencode(json_encode(array(2,3)));?>" id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a></div>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    <?php }?>
                    
                    <!-- Direccion  -->
                    <?php if($cn_direccion){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Dirección</div>
                      <div class="AccordionPanelContent">
                      <div id="compartetutor" style="width:94.7%" > </div>
                        <div id="ladireccion">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%" height="50"><p align="right">Calle</p></td>
                            <td width="31%"><input name="calle" type="text" id="calle" style="width:80%; margin-left:10px;" title='requiere' required value="<?php echo $enrolado->calle_domicilio; ?>"></td>
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
                            <td><input name="cp" type="text" title='requiere' required id="cp" style="width:75%; margin-left:10px;" value="<?php echo $enrolado->cp_domicilio; ?>" maxlength="5" ></td>
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
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><div class="input-append" style="width:100%"><input name="localidadT" type="text" title='requiere' required="title='requiere' required" id="localidadT" style="width:68%; margin-left:10px;" value="" readonly="readonly">
                              <input name="localidad" type="hidden" id="localidad" value=""/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Direccion/1/radio/0/localidad/localidadT/1/1/<?php echo urlencode(json_encode(array(2,4,5)));?>/<?php echo urlencode(json_encode(array(2,3)));?>" id="fba1" class="btn btn-primary">Seleccionar <i class="icon-search"></i></a></div>
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
                    
                    <!-- alergias y reacciones:  -->
                    <?php if($cn_alergia){ ?>
                  <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Historial de Alergias y Reacciones Febriles</div>
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
                                        <th width="50%" align="left">IRA</th>
                                        <th width="40%" align="left">Fecha</th>
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
                                        <th width="50%" align="left">EDA</th>
                                        <th width="40%" align="left">Fecha</th>
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
                                        <th width="50%" align="left">Consulta</th>
                                        <th width="40%" align="left">Fecha</th>
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
                                        <th width="18%" align="left">Talla (cm)</th>
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
                    
                    </td>
            </tr>
            <tr>
                <td>
                <br />
                <span id="enviandoof" style="margin-left:-20px;">
                <button class="btn btn-primary" type="submit" name="guardar" id="guardar" onclick="return validarFormulario('enrolar')">Guardar <i class="icon-hdd"></i></button>
                <button class="btn btn-primary" type="button" onclick="window.location.href='/<?php echo DIR_TES?>/enrolamiento/'" >Cancelar <i class="icon-arrow-left"></i></button>
                </span>
    			
                </td>
            </tr>
        </table>
	</td></tr></table>

<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("Accordion1", { useFixedPanelHeights: false, defaultPanel: 0 });
</script>
<?php 
}
else
{
 echo "<div class='$infoclass'>".$msgResult."</div><div><br>";
 echo '<a href="" class="btn btn-primary" onclick="window.location.href=\'/'.DIR_TES.'/enrolamiento/\';return false;">Regresar</a></div>';
}

function getArray($array,$id,$nu)
{
	$i=0; $grid="";
	foreach($array as $dato)
	{
		$i++;
		$dato=(array)$dato;
		$descripcion=$dato["descripcion"];
		$fecha=$dato["fecha"];
		$x=$dato["id"];
		$clase="row2";
		if($i%2)$clase="row1";
		$num=$i;
		if($i<10)$num="0".$i;
		$grid.= '<span id="r'.$id.$num.'" ><div class="'.$clase.'" >
				<table width="100%" >
				<tr>
					<th width="10%" >'.$num.'</th>
					<th width="50%" align="left"><select name="'.$id.'[]" id="'.$id.$num.'" class="requiere" title="requiere" required style="width:95%;"></select>
					<script>$("#'.$id.$num.'").load("/tes/enrolamiento/catalog_select/'.$id.'/'.$x.'");</script>
					</th>
					<th width="40%" align="left"><input name="f'.$id.'[]" type="text" id="f'.$id.$num.'" value="'.date("d-m-Y",strtotime($fecha)).'"></th>
				</tr>
				</table> 
			  </div></span>
			  <script>
	$(document).ready(function()
	{
		$("#f'.$id.$num.'").datepicker();
	});</script>';
			  
		 
	 }
	
	$grid.='<input type="hidden" id="'.$nu.'" value="'.$i.'" />';
	return $grid;
}
?>