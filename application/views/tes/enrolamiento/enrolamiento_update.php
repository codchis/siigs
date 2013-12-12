    <link href="/resources/css/grid.css" rel="stylesheet" type="text/css" /> 
    <link href="/resources/SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css" /> 
	<script src="/resources/SpryAssets/SpryAccordion.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="/resources/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="/resources/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link   type="text/css" href="/resources/fancybox/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet"/>
    <script>
	$(document).ready(function()
	{
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
					$.ajax({
					type: "POST",
					data: {
						'claves':[array] ,
						'desglose':1 },
					url: '/<?php echo DIR_SIIGS.'/raiz/getDataTreeFromId';?>',
					})
					.done(function(dato)
					{
						if(dato)
						{
							var obj = jQuery.parseJSON( dato );
							document.getElementById(uri.substr(uri.search("/")+1,uri.length)).value=obj[0]["descripcion"];
						}
						if(uri.substr(uri.search("/")+1,uri.length)=="lnacimientoT")
						getcurp();
					});
				}
			}						
		}); 
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
		
		$.ajax({
		type: "POST",
		data: {
			'claves':[<?php echo $enrolado->id_asu_um_tratante;?>] ,
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
		$("#alergias").load("/tes/Enrolamiento/catalog_check/alergia/checkbox/3/<?php echo $alerg;?>/tipo");	
		$("#tbenef").load("/tes/Enrolamiento/catalog_check/afiliacion/checkbox/2/<?php echo $afili;?>");		
		$("#sangre").load("/tes/Enrolamiento/catalog_select/tipo_sanguineo/<?php echo $enrolado->sangre; ?>");	
		$("#nacionalidad").load("/tes/Enrolamiento/catalog_select/nacionalidad/<?php echo $enrolado->nacionalidadid; ?>");
		$("#compania").load("/tes/Enrolamiento/catalog_select/operadora_celular/<?php echo $enrolado->operadoraid; ?>");
		$("#companiaT").load("/tes/Enrolamiento/catalog_select/operadora_celular/<?php echo $enrolado->operadoraTid; ?>");
		$("#nombre,#paterno,#materno,#fnacimiento,#lnaciminetoT").blur(function()
		{       
			getcurp();
		});	
		
		$("#captura").click(function(e) {
            habilitarTutor();
        });
		$("#buscarCurp").click(function(e) {
            buscarTutor($("#buscar").val());
			return false;
        });
		$("#curpT").blur(function(e) {
            buscarTutor(this.value);
        });
		habilitarTutor();
	});
	function habilitarTutor()
	{
		if(document.getElementById("captura").checked)
		{
			$("#nombreT").removeAttr("disabled");
			$("#paternoT").removeAttr("disabled");
			$("#maternoT").removeAttr("disabled");
			$("#celularT").removeAttr("disabled");
			$("#telefonoT").removeAttr("disabled");
			$("#companiaT").removeAttr("disabled");
			$("#sexoT_1").removeAttr("disabled");
			$("#sexoT_2").removeAttr("disabled");
		}
		else
		{
			$("#nombreT").attr("disabled",true);
			$("#paternoT").attr("disabled",true);
			$("#maternoT").attr("disabled",true);
			$("#celularT").attr("disabled",true);
			$("#telefonoT").attr("disabled",true);
			$("#companiaT").attr("disabled",true);
			$("#sexoT_1").attr("disabled",true);	
			$("#sexoT_2").attr("disabled",true);
			var buscar=$("#curpT").val();
			if($("#buscar").val()!="")
				buscar=$("#buscar").val();
			if(buscar!="")		
			buscarTutor(buscar);		
		}
	}
	function buscarTutor(buscar)
	{	
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
					
				}
				else
				{
					$("#buscarError").html('<strong>'+obj[0]["error"]+'&nbsp;</strong>');		
				}
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
		var ed=$("#lnacimientoT").val().substr($("#lnacimientoT").val().search(",")+1,$("#lnacimientoT").val().length);
		ed=$.trim(ed);
		var a=fn.substr(0,4);
		var m=fn.substr(5,2);
		var d=fn.substr(8,2);
		if(ap!=""&&am!=""&&no!=""&&se!=""&&fn!=""&&ed!="")
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
		
		campo = '<span id="r'+id+num+'" ><div class="'+miclase+'" style="80%"><table width="90%" >  <tr>   <th width="10%">'+num+'</th>  <th width="50%"><select name="'+id+'[]" id="'+id+num+'" required="required" style="width:95%;"></select></th>  <th width="40%"><input name="f'+id+'[]" type="date" id="f'+id+num+'" ></th> </tr> </table> </div></span>';
		$("#"+a).append(campo);
		
		$("#"+id+num).load("/tes/Enrolamiento/catalog_select/"+id);
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
		
		campo = '<span id="r'+"CNu"+num+'" ><div class="'+miclase+'" style="80%"><table width="90%" >  <tr>   <th width="10%">'+num+'</th>  <th width="18%"><input type="number" step=".01" min="0" name="cpeso[]" id="cpeso'+num+'" required="required" style="width:85%;"></th> <th width="18%"><input type="number" step=".01" min="0" max="3" name="caltura[]" id="caltura'+num+'" required="required" style="width:85%;"></th>  <th width="18%"><input type="number" step=".01" min="0" name="ctalla[]" id="ctalla'+num+'" required="required" style="width:85%;"></th>  <th width="36%"><input name="fCNu[]" type="date" id="fCNu'+num+'" ></th> </tr> </table> </div></span>';
		$("#cNu").append(campo);
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
	</script>
	<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
    	<!-- mensaje-->
        <?php 	
			if(!empty($msgResult))
			echo "<div class='$infoclass'>".$msgResult."</div>";
			if(validation_errors())
			echo "<div class='error'>".validation_errors()."</div>"; 
			echo form_open(DIR_TES.'/enrolamiento/update/'.$enrolado->id); 
		?>
      <!-- mensaje -->
        	<table width="100%">
            <tr>
                <td>
                  <div id="Accordion1" class="Accordion" tabindex="0" style="margin-left:-20px;">
                  
                  <!-- Datos basicos -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos Basicos</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><input name="nombre" type="text" id="nombre" style="width:80%; margin-left:10px;" required value="<?php echo $enrolado->nombre; ?>">
                            <input name="id" type="hidden" id="id" value="<?php echo $id;?>"  /></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%">
                              <label style=" margin-left:10px;">
                                <input type="radio" name="sexo" value="M" <?php if($enrolado->sexo=="M") echo "checked"; ?> id="sexo_1" onclick="getcurp();" required >
                                Masculino</label>
                              <label>
                                <input type="radio" name="sexo" value="F" <?php if($enrolado->sexo=="F") echo "checked"; ?> id="sexo_2" onclick="getcurp();">
                                Femenino</label>
                             </td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><input name="paterno" type="text" id="paterno" style="width:80%; margin-left:10px;" required value="<?php echo $enrolado->apellido_paterno; ?>"></td>
                            <td><p align="right">Tipo de Sangre</p></td>
                            <td>
                              <select name="sangre" id="sangre" style="width:80%; margin-left:10px;" required>                           
                            
                            </select></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><input name="materno" type="text" id="materno" style="width:80%; margin-left:10px;" required value="<?php echo $enrolado->apellido_materno; ?>"></td>
                            <td><p align="right">Fecha de Nacimiento</p></td>
                            <td><input name="fnacimiento" type="date" id="fnacimiento" style="width:74%; margin-left:10px;" required value="<?php echo $enrolado->fecha_nacimiento; ?>"></td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar de Nacimiento</p></td>
                            <td colspan="3"><input name="lnacimientoT" type="text" required id="lnacimientoT" style="width:68%; margin-left:10px;" value="" readonly="readonly">
                            	<input name="lnacimiento" type="hidden" id="lnacimiento" value="">                              
                              <a href='/<?php echo DIR_TES?>/tree/create/TES/Lugar de Nacimiento/1/radio/0/lnacimiento/lnacimientoT/1/1/<?php echo urlencode(json_encode(array(3,4,5)));?>/' id="fba1" class="cat">Seleccionar</a><div id="aqui"></div>
                              </td>
                            </tr>
                          <tr>
                            <td><p align="right">CURP</p></td>
                            <td ><input name="curp" type="text" id="curp"  style="letter-spacing:1px; width:48%;margin-left:10px;" value="<?php echo substr($enrolado->curp,0,12); ?>">
                            <input name="curp2" type="text" id="curp2"  style="letter-spacing:1px; width:20%" required value="<?php echo substr($enrolado->curp,12,15); ?>"></td>
                            <td><p align="right">Nacionalidad</p></td>
                            <td><select name="nacionalidad" id="nacionalidad" style="width:80%; margin-left:10px;" required="required">
                            </select></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    
                    <!-- Tipo de Beneficiario:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Tipo de Beneficiario</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                       	<div id="tbenef">
                            
                            </div>
                      	</code>
                      </div>
                    </div>
                    <!-- Tutor -->
                  
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Datos de la Madre o Tutor</div>
                      <div class="AccordionPanelContent" >
                      
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td colspan="2"><p align="right">Madres o Tutores ya Capturados</p></td>
                            <td>
                              <input name="buscar" type="text" id="buscar" style="width:100%; margin-left:10px;" <?php echo set_value('buscar', '') ?>/>
                            </td>
                            <td><a href="#" id="buscarCurp" class="cat">Buscar</a></td>
                          </tr>
                          <tr>
                            <td colspan="2"><p align="right">Capturar Nueva Madre o Tutor</p>                              
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
                            <td width="31%"><input name="curpT" type="text" required id="curpT" style="width:80%; margin-left:10px;"  value="<?php echo $enrolado->curpT; ?>" maxlength="18" /></td>
                            <td width="25%"><p align="right">Sexo</p></td>
                            <td width="25%">
                              <label style=" margin-left:10px;">
                                <input type="radio" name="sexoT" value="M" <?php if( $enrolado->sexoT=="M") echo "checked"; ?> id="sexoT_1" disabled="disabled">
                                Masculino</label>
                              <label>
                                <input type="radio" name="sexoT" value="F" <?php if( $enrolado->sexoT=="F") echo "checked"; ?> id="sexoT_2" disabled="disabled" >
                                Femenino</label>
                             </td>
                          </tr>
                          <tr>
                            <td width="19%"><p align="right">Nombre</p></td>
                            <td width="31%"><input name="nombreT" type="text" disabled="disabled" required="required" id="nombreT" style="width:80%; margin-left:10px;" value="<?php  echo set_value('nombreT', $enrolado->nombreT) ; ?>" /></td>
                            <td><p align="right">Telefono de Casa</p></td>
                            <td><input name="celularT" type="text" disabled="disabled" id="celularT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->celularT; ?>" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Paterno</p></td>
                            <td><input name="paternoT" type="text" disabled="disabled" required="required" id="paternoT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->paternoT; ?>" /></td>
                            <td><p align="right">Celular</p></td>
                            <td><input name="telefonoT" type="text" disabled="disabled" id="telefonoT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->telefonoT; ?>" /></td>
                          </tr>
                          <tr>
                            <td><p align="right">Apellido Materno</p></td>
                            <td><input name="maternoT" type="text" disabled="disabled" required="required" id="maternoT" style="width:80%; margin-left:10px;" value="<?php echo $enrolado->maternoT; ?>" /></td>
                            <td><p align="right">Compania Celular</p></td>
                            <td><select name="companiaT" id="companiaT" style="width:85%; margin-left:10px;" disabled="disabled">
                            </select></td>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!--  Registro civil -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Registro Civil</div>
                      <div class="AccordionPanelContent" >
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Fecha</p></td>
                            <td width="31%"><input name="fechacivil" type="date" id="fechacivil" style="width:75%; margin-left:10px;"  value="<?php echo date("Y-m-d",strtotime($enrolado->fecha_registro)); ?>"></td>
                            <td width="25%"><p align="right">&nbsp;</p></td>
                            <td width="25%">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><p align="right">Lugar</p></td>
                            <td colspan="3"><input name="lugarcivilT" type="text" id="lugarcivilT" style="width:68%; margin-left:10px;"  value="" readonly="readonly">
                              <input name="lugarcivil" type="hidden" id="lugarcivil"  value=""/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Lugar de Nacimiento/1/radio/0/lugarcivil/lugarcivilT/1/1/<?php echo urlencode(json_encode(array(null)));?>/" id="fba1" class="cat">Seleccionar</a>
                          </tr>
                        </table>
                        <br />
                      
                      </div>
                    </div>
                    
                    <!-- Direccion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Dirección</div>
                      <div class="AccordionPanelContent">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left:15px;">
                          <tr>
                            <td width="19%"><p align="right">Calle</p></td>
                            <td width="31%"><input name="calle" type="text" id="calle" style="width:80%; margin-left:10px;" required value="<?php echo $enrolado->calle_domicilio; ?>"></td>
                            <td width="25%"><p align="right">Número</p></td>
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
                            <td><input name="cp" type="text" required id="cp" style="width:75%; margin-left:10px;" value="<?php echo $enrolado->cp_domicilio; ?>" maxlength="5"></td>
                          </tr>
                          <tr>
                            <td><p align="right">Localidad</p></td>
                            <td colspan="3"><input name="localidadT" type="text" required="required" id="localidadT" style="width:68%; margin-left:10px;" value="" readonly="readonly">
                              <input name="localidad" type="hidden" id="localidad" value=""/>
                              <a href="/<?php echo DIR_TES?>/tree/create/TES/Lugar de Nacimiento/1/radio/0/localidad/localidadT/1/1/<?php echo urlencode(json_encode(array(3,4,5)));?>/" id="fba1" class="cat">Seleccionar</a>
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
                        <br />
                      </div>
                    </div>
                    
                    <!-- alergias y reacciones:  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Historial de Alergias y Reacciones Febriles</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        	<div id="alergias">
                            
                            </div>
                        </code>
                      </div>
                    </div>
                    
                    
                    <!-- vacunacion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Vacunación</div>
                      <div class="AccordionPanelContent"><br />                      
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
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
                              <td valign="top"> 
                                   <input type="button" value="Agregar" onclick="add('vacuna','vn','vc');" style="height:40px; width:80px;"/> 
                                   <input type="button" value="Quitar"  onclick="rem('vacuna','vn');" style="height:40px; width:80px;"/></td>
                              </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- ira  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de IRA</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
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
                                   <input type="button" value="Agregar" onclick="add('ira','in','ic');" style="height:40px; width:80px;"/> 
                                   <input type="button" value="Quitar"  onclick="rem('ira','in');" style="height:40px; width:80px;"/></td>
                              </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- eda  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de EDA</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
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
                                   <input type="button" value="Agregar" onclick="add('eda','en','ec');" style="height:40px; width:80px;"/> 
                                   <input type="button" value="Quitar"  onclick="rem('eda','en');" style="height:40px; width:80px;"/></td>
                              </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- consulta  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Consulta</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
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
                                   <input type="button" value="Agregar" onclick="add('consulta','ncc','ccc');" style="height:40px; width:80px;"/> 
                                   <input type="button" value="Quitar"  onclick="rem('consulta','ncc');" style="height:40px; width:80px;"/>  
                                   
                                  </td>
                              </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    
                    <!-- accion nutricional  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control de Acción Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
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
                                   <input type="button" value="Agregar" onclick="add('accion_nutricional','nac','can');" style="height:40px; width:80px;"/> 
                                   <input type="button" value="Quitar"  onclick="rem('accion_nutricional','nac');" style="height:40px; width:80px;"/></td>
                              </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>
                    <!-- nutricion  -->
                    <div class="AccordionPanel">
                      <div class="AccordionPanelTab">Control Nutricional</div>
                      <div class="AccordionPanelContent"><br />
                      	<code style="margin-left:20px; width:60%">
                        <table>
                            <tr>
                                <td width="85%" valign="top">
                                <div class="detalle" style="80%">
                                  <table width="100%" >
                                    <tr>
                                        <th width="10%" >No</th>
                                        <th width="18%" align="left">Peso</th>
                                        <th width="18%" align="left">Altura</th>
                                        <th width="18%" align="left">Talla</th>
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
					<th width="18%" align="left"><input type="number" step=".01" min="0" name="cpeso[]" id="cpeso'.$num.'" required="required" style="width:85%;" value="'.$peso.'"></th> 
					<th width="18%"><input type="number" step=".01" min="0" max="3" name="caltura[]" id="caltura'.$num.'" required="required" style="width:85%;" value="'.$altura.'"></th>  
					<th width="18%"><input type="number" step=".01" min="0" name="ctalla[]" id="ctalla'.$num.'" required="required" style="width:85%;" value="'.$talla.'"></th>  
					<th width="36%"><input name="fCNu[]" type="date" id="fCNu'.$num.'" value="'.date("Y-m-d",strtotime($fecha)).'"></th>
				</tr>
				</table> 
			  </div></span>';
			  
		 
	 }
	
	$grid.='<input type="hidden" id="nNu" value="'.$i.'" />';
	echo $grid;
	?>
                                  <div id="cNu">
                                  </div>                           
                                 </td>
                                 <td valign="top"> 
                                   <input type="button" value="Agregar" onclick="addNutricional();" style="height:40px; width:80px;"/> 
                                   <input type="button" value="Quitar"  onclick="remNutricional();" style="height:40px; width:80px;"/>  
                                   
                                  </td>
                              </tr>                     
                          </table>
                        </code>
                      </div>
                    </div>                                        
                    
                    </td>
            </tr>
            <tr>
                <td>
                <span id="enviandoof" style="margin-left:-20px;">
                <input type="submit" name="guardar" id="guardar" value="Guardar" />
                <input type="button" value="Cancelar" onclick="window.location.href='/<?php echo DIR_TES?>/enrolamiento/'" />
                </span>
    			
                </td>
            </tr>
        </table>
	</td></tr></table>

<script type="text/javascript">
var Accordion1 = new Spry.Widget.Accordion("Accordion1", { useFixedPanelHeights: false, defaultPanel: 0 });
</script>
<?php
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
					<th width="50%" align="left"><select name="'.$id.'[]" id="'.$id.$num.'" required="required" style="width:95%;"></select>
					<script>$("#'.$id.$num.'").load("/tes/Enrolamiento/catalog_select/'.$id.'/'.$x.'");</script>
					</th>
					<th width="40%" align="left"><input name="f'.$id.'[]" type="date" id="f'.$id.$num.'" value="'.date("Y-m-d",strtotime($fecha)).'"></th>
				</tr>
				</table> 
			  </div></span>';
			  
		 
	 }
	
	$grid.='<input type="hidden" id="'.$nu.'" value="'.$i.'" />';
	return $grid;
}
?>