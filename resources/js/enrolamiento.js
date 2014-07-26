function obtener_um_responsabilidad()
{
	var ageb=$("#ageb").val();
	var loca=$("#localidad").val();
	document.getElementById("umt").value="";
	document.getElementById("um").value="";
	if(ageb!=""&&loca!="")
	{
		$.ajax({
		type: "POST",
		url: 'http://'+window.location.host+'/tes/enrolamiento/searchum/'+loca+'/'+ageb,
		})
		.done(function(dato)
		{
			if(dato)
			{
				var obj = jQuery.parseJSON( dato );
				var cv=obj["clave"];
				var um=obj["valor"];
				if(cv==0)
				{
					$("#tieneum").html('<div style="width:100%" class="error">Esta fuera del área de responsabilidad verifique AGEB y LOCALIDAD de la sección DIRECCIÓN</div>');
				}
				else if(cv==1)
				{
					$("#tieneum").html("<span></span>");
					$.ajax({
					type: "POST",
					data: {
						'claves':[um] ,
						'desglose':5 },
					url: '/siigs/raiz/getDataTreeFromId',
					})
					.done(function(datos)
					{
						if(datos)
						{
							var obj1 = jQuery.parseJSON( datos );
							var des=obj1[0]["descripcion"];
							var ed=des.split(",");
							ed=ed[ed.length-2];
							des=des.replace(ed+",", "");
							document.getElementById("umt").value=des;
							document.getElementById("um").value=um;
						}
					});
				}
			}
		});
	}
}
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
	if($("#buscar").val()!=""||$("#curpT").val()!="")
	{
		buscar=buscar.replace(" ","");
		buscar=buscar.replace("=","");
		$("#idtutor").val("");
		/*$("#nombreT").val("");
		$("#paternoT").val("");
		$("#maternoT").val("");
		$("#celularT").val("");
		
		$("#telefonoT").val("");
		$("#companiaT").val("");
		$("#sexoT_1").attr("checked",false);
		$("#sexoT_2").attr("checked",false);
		$("#buscarError").html('');*/
	//var buscar = $("#buscar").val();
	$.ajax({
		url: "/tes/enrolamiento/data_tutor/"+buscar,
		type: "POST",
		data: "json",
		success:function(data){
			var obj = jQuery.parseJSON( data );
			//console.debug(obj);
			if(obj[0]["error"]=="")
			{
				$("#buscarError").html('');
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
				if($("#compartetutor"))
				$.get('/tes/enrolamiento/brothers_search/'+$("#idtutor").val(), function(respuesta) 
				{
					if(respuesta.length>5)
					{
						var obj = jQuery.parseJSON( respuesta );
						if(document.getElementById("hermanos"))
						{
							$("#hermanos").remove();	
							$("#compartetutor").attr("class","");	
						}
						
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
}
function limpiar_direccion()
{
	$('#ladireccion').data('old-state', $('#ladireccion').html());
	$('#ladireccion').html($('#ladireccion').data('old-state'));
	comparar_captura();
}
function importarDatos(id)
{
	$.get('/tes/enrolamiento/brother_found/'+id, function(respuesta) 
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
			url: '/siigs/raiz/getDataTreeFromId',
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
					document.getElementById("localidadT").value=des;
					comparar_captura();
				}
			});
			obtener_um_responsabilidad();
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

function edita_curp()
{
    $("#precurp").val('0');
    $("#editacurp").css({'visibility':'hidden'});
    $("#curp , #curp2").removeAttr('readonly');
    return false;
}

function calcula_curp()
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
	
	if(ap!=""&&am!=""&&no!=""&&(se!=""&&typeof(se)!="undefined")&&fn!=""&&ed!="")
	{
		if(x.length>3)
		{
                        $("#precurp").val('0');
			$("#nocurp").html('<span style="color:blue">Calculando Curp... Espere</span>');
			$("#curp").val("");
			$("#curpl").html("");		
			$("#curp2").val("");
			$.ajax({
				url: "/tes/obtenercurp/calculacurp/"+ap+"/"+am+"/"+no+"/"+d+"/"+m+"/"+a+"/"+se+"/"+ed+"/2",
				type: "POST",
				data: "json",
				success:function(data){
					if(data)
					{
						var obj = jQuery.parseJSON( data );
						var curp=obj[0]["curp"];
						$("#curp").val(curp.substr(0,curp.length-5));
						$("#curpl").html('<strong>'+curp.substr(0,curp.length-5)+'&nbsp;</strong>');		
						$("#curp2").val(curp.substr(curp.length-5,5));
                                                $("#precurp").val("0");
                                                $("#curp , #curp2").attr('readonly',"readonly");
						$("#nocurp").html('<span style="color:green">Curp calculada correctamente</span>');
                                                $("#precurp").val('1');
                                                $("#editacurp").css({'visibility':'visible'});
					}
					else
					{
						$("#nocurp").html('<span style="color:red">No se pudo calcular la curp. Por favor dig&iacute;tela</span>');	
						//calcular_curp(ap,am,no,d,m,a,se,ed,0);
					}
				}
			});
		}
		else 
                {
                    $("#fnacimiento").val("");
                    $("#fnacimiento").attr("placeholder","dd-mm-yyyy"); $("#fnacimiento").focus();
                }
	}
        else
        {
            $("#nocurp").html('<span style="color:blue">Datos insuficientes...</span>');
            $('#nocurp').fadeOut(2000,function(){
                $('#nocurp').html('');
                $('#nocurp').fadeIn(500);
            });
        }
	return false;
}

function getcurp(event)
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
	
	if(ap!=""&&am!=""&&no!=""&&(se!=""&&typeof(se)!="undefined")&&fn!=""&&ed!="")
	{
		if(x.length>3)
		{
			$("#nocurp").html('<span style="color:blue">Buscando Curp... Espere</span>');
			$("#curp").val("");
			$("#curpl").html("");		
			$("#curp2").val("");
			$.ajax({
				url: "/tes/obtenercurp/curp/"+ap+"/"+am+"/"+no+"/"+d+"/"+m+"/"+a+"/"+se+"/"+ed+"/2",
				type: "POST",
				data: "json",
				success:function(data){
					if(data)
					{
						var obj = jQuery.parseJSON( data );
						var curp=obj[0]["curp"];
						$("#curp").val(curp.substr(0,curp.length-5));
						$("#curpl").html('<strong>'+curp.substr(0,curp.length-5)+'&nbsp;</strong>');		
						$("#curp2").val(curp.substr(curp.length-5,5));
                                                $("#precurp").val("0");
                                                $("#curp , #curp2").css({disabled:"disabled"});
						$("#nocurp").html('<span style="color:green">Curp encontrada en la base de la federación</span>');		
					}
					else
					{
						$("#nocurp").html('<span style="color:red">Curp no encontrada en la base de la federación calculando manualmente... Espere</span>');	
						calcular_curp(ap,am,no,d,m,a,se,ed,0);
					}
				}
			});
		}
		else {$("#fnacimiento").val("");$("#fnacimiento").attr("placeholder","dd-mm-yyyy"); $("#fnacimiento").focus();};
	}
	return false;
}
function getcurpTutor()
{
	if(document.getElementById("fechaT"))
	{
		var ap=omitirAcentos($("#paternoT").val());
		var am=omitirAcentos($("#maternoT").val());
		var no=omitirAcentos($("#nombreT").val());
		var se=$("input[name='sexoT']:checked").val();
		var fn=$("#fechaT").val();
		var ed=$("#edoT").val();
		var d=fn.substr(0,2);
		var m=fn.substr(3,2);
		var a=fn.substr(6,4);
		var x=parseInt(a)+"";
		
		if(ap!=""&&am!=""&&no!=""&&(se!=""&&typeof(se)!="undefined")&&fn!=""&&ed!="")
		{
			if(x.length>3)
			{
				$("#errorcurptutor").html('<span style="color:blue">Calculando Curp... Por favor espere</span>');
				$("#curpT").val("");
				$.ajax({
					url: "/tes/obtenercurp/calculacurp/"+ap+"/"+am+"/"+no+"/"+d+"/"+m+"/"+a+"/"+se+"/"+ed+"/2",
					type: "POST",
					data: "json",
					success:function(data){
						if(data)
						{
							var obj = jQuery.parseJSON( data );
							var curp=obj[0]["curp"];
							$("#curpT").val(curp);
							$("#errorcurptutor").html('<span style="color:green">Curp calculada correctamente</span>');		
						}
						else
						{
							$("#errorcurptutor").html('<span style="color:red">No se pudo calcular la curp. Por favor dig&iacute;tela</span>');	
							//calcular_curp(ap,am,no,d,m,a,se,ed,1);
						}
					}
				});
			}
		}
	}
	return false;
}
function calcular_curp(ap,am,no,d,m,a,se,ed,op)
{
	$.ajax({
		url: "/tes/obtenercurp/calcular_curp/"+ap+"/"+am+"/"+no+"/"+d+"/"+m+"/"+a+"/"+se+"/"+ed+"/2",
		type: "POST",
		data: "json",
		success:function(data){
			if(data)
			{
				var obj = jQuery.parseJSON( data );
				var curp=obj[0]["curp"];
				if(op==1)
				{
					$("#curpT").val(curp);
					$("#errorcurptutor").html('<span style="color:green">Curp calculada correctamente</span>');	

				}
				else
				{
					$("#curp").val(curp.substr(0,curp.length-5));
					$("#curpl").html('<strong>'+curp.substr(0,curp.length-5)+'&nbsp;</strong>');		
					$("#curp2").val(curp.substr(curp.length-5,5));
					$("#nocurp").html('<span style="color:green">Curp calculada correctamente</span>');	
				}
			}
			else
			{
				if(op==1)
					$("#errorcurptutor").html('<span style="color:red">No se pudo calcular la curp. Por favor dig&iacute;tela</span>');
				else
					$("#nocurp").html('<span style="color:red">No se pudo calcular la curp. Por favor dig&iacute;tela</span>');	
			}
		}
	});
}
function add(id,n,a)
{	
	num=document.getElementById(n).value*1;	
	num=num+1;
	document.getElementById(n).value=num;
	var miclase="";
	if((num%2)==0) miclase="row2"; else miclase="row1";
	if(num<10)num="0"+num;
	var campo_mas=""; var ax="99%"; var by="80%"; var ha="50%",hb="40%";
	if(id=="ira"||id=="eda"||id=="consulta")
	{
		campo_mas='<th width="20%"><select name="tratamiento'+id+'[]" id="tratamiento'+id+num+'" style="width:99%;" onkeydown="return entertab(event,0)"></select></th><th width="27%"><select name="tratamiento_des'+id+'[]" id="tratamiento_des'+id+num+'" style="width:99%;"></select></th>';
		ax="99%"; by="70%"; ha="28%"; hb="15%";
	}
	if(id=="vacuna")	
	{
		campo_mas='<th width="20%"><input type="text" name="ffolio'+id+'[]" id="ffolio'+id+num+'" style="width:87%;" onkeydown="return entertab(event,0)"></th>';
		ax="98%"; by="78%"; ha="40%"; hb="30%";
	}
	campo = '<span id="r'+id+num+'" ><div class="'+miclase+'" style="width:100%"><table width="100%" >  <tr>   <th width="10%">'+num+'</th>  <th width="'+ha+'"><select name="'+id+'[]" id="'+id+num+'" title="requiere" class="requiere" required style="width:'+ax+'" onkeydown="return entertab(event,0)"></select></th>  <th width="'+hb+'"><input name="f'+id+'[]" type="text" id="f'+id+num+'" style="width:'+by+'" onkeydown="return entertab(event,0)"></th>'+campo_mas+'</tr> </table> </div></span>';
	
	$("#"+a).append(campo);
	
	$("#f"+id+num).val($.datepicker.formatDate('dd-mm-yy', new Date()));
	$("#f"+id+num).datepicker(optionsFecha );
	$("#"+id+num).load("/tes/enrolamiento/catalog_select/"+id);
	if(id=="ira"||id=="eda"||id=="consulta")
	{
		$("#tratamiento"+id+num).load("/tes/enrolamiento/tratamiento_select/activo/1/0/tipo");

		$("#tratamiento"+id+num).click(function(e) 
		{
			num=this.id.replace(/\D/g,'');
			$("#tratamiento_des"+id+num).load("/tes/enrolamiento/tratamiento_select/tipo/"+encodeURIComponent(this.value)+"/0/descripcion/");
		});
	}
	
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
	
	campo = '<span id="r'+"CNu"+num+'" >\n\
                <div class="'+miclase+'" style="width:100%">\n\
                <table width="100%" >\n\
                    <tr>\n\
                        <th width="10%">'+num+'</th>\n\
                        <th width="18%"><input type="number" step=".001" min="0" name="cpeso[]" id="cpeso'+num+'" style="width:85%;" onkeydown="return entertab(event,0)"></th>\n\
                        <th width="18%"><input type="number" step=".001" min="0" max="300" name="caltura[]" id="caltura'+num+'" style="width:85%;" onkeydown="return entertab(event,0)"></th>\n\
                        <th width="18%"><input type="number" step=".001" min="0" name="ctalla[]" id="ctalla'+num+'" style="width:85%;" onkeydown="return entertab(event,0)"></th>\n\
                        <th width="18%"><input type="number" step=".001" min="0" name="chemoglobina[]" id="chemoglobina'+num+'" style="width:85%;" onkeydown="return entertab(event,0)"></th>\n\
                        <th width="18%"><input name="fCNu[]" type="text" id="fCNu'+num+'" style="width:85%"></th> \n\
                    </tr>\n\
                </table>\n\
                </div>\n\
            </span>';
	$("#cNu").append(campo);
	$("#fCNu"+num).val($.datepicker.formatDate('dd-mm-yy', new Date()));
	$("#fCNu"+num).datepicker(optionsFecha );
	
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

function add_fecha_edo()
{	
	campo = '<span id="_fecha_edo" ><p>Fecha: <input id="fechaT" style="height:25px; width:150px; margin-top:-6px;" onkeydown="return entertab(event,100)" tabindex="19">&nbsp; Estado: <select id="edoT" onkeydown="return entertab(event,25)" tabindex="20"><option value="" >Seleccione</option><option value="AGUASCALIENTES">AGUASCALIENTES</option><option value="BAJA CALIFORNIA NORTE">BAJA CALIFORNIA</option><option value="BAJA CALIFORNIA SUR">BAJA CALIFORNIA SUR</option><option value="CAMPECHE">CAMPECHE</option><option value="CHIAPAS">CHIAPAS</option><option value="CHIHUAHUA">CHIHUAHUA</option><option value="COAHUILA">COAHUILA</option><option value="COLIMA">COLIMA</option><option value="DISTRITO FEDERAL">DISTRITO FEDERAL</option><option value="DURANGO">DURANGO</option><option value="GUANAJUATO">GUANAJUATO</option><option value="GUERRERO">GUERRERO</option><option value="HIDALGO">HIDALGO</option><option value="JALISCO">JALISCO</option><option value="MEXICO">MEXICO</option><option value="MORELOS">MORELOS</option><option value="MICHOACAN">MICHOACAN</option><option value="NAYARIT">NAYARIT</option><option value="NUEVO LEON">NUEVO LEON</option><option value="OAXACA">OAXACA</option><option value="PUEBLA">PUEBLA</option><option value="QT">QUERETARO</option><option value="QUINTANA ROO">QUINTANA ROO</option><option value="SAN LUIS POTOSI">SAN LUIS POTOSI</option><option value="SINALOA">SINALOA</option><option value="SONORA">SONORA</option><option value="TABASCO">TABASCO</option><option value="TAMAULIPAS">TAMAULIPAS</option><option value="TLAXCALA">TLAXCALA</option><option value="VERACRUZ">VERACRUZ</option><option value="YUCATAN">YUCATAN</option><option value="ZACATECAS">ZACATECAS</option><option value="NACIDO EN EL EXTRANJERO">EXTRANJERO</option></select></p></span>';
	$("#tutorcurp").append(campo);
	$("#fechaT").datepicker(optionsFecha );
	$("#fechaT,#edoT").change(function()
	{       
		getcurpTutor();
	});	
}
function rem_fecha_edo()
{
	$("#_fecha_edo").remove();
}
function cleanForm()
{
	var valor=$("#alert").html();
	if(valor.search("incorrecto")<0)
	limpiaformulario("enrolar");
	else
	$("#alert").css("display","")
}
function comparar_captura()
{
	var no=encodeURIComponent($("#nombre").val());
	var pa=encodeURIComponent($("#paterno").val());
	var ma=encodeURIComponent($("#materno").val());
	var ln=encodeURIComponent($("#lnacimientoT").val().split(",")[0]);
	var cu=encodeURIComponent($("#curp").val()+$("#curp2").val());
	var fn=encodeURIComponent($("#fnacimiento").val());
	var ct=encodeURIComponent($("#curpT").val());
	var ca=encodeURIComponent($("#calle").val());
	var re=encodeURIComponent($("#referencia").val());
	var co=encodeURIComponent($("#colonia").val());
	if(no!=""&&pa!=""&&ma!=""&&ln!="")
	{
		$.get('/tes/enrolamiento/paciente_similar/'+no+'/'+pa+'/'+ma+'/'+cu+'/'+fn+'/'+ln+'/'+ca+'/'+re+'/'+co+'/'+ct, function(respuesta) 
		{
			if(respuesta.length>5)
			{
				if($("#simi"))
				$("#simi").remove();
				var obj = jQuery.parseJSON( respuesta );
				var campo = '<span id="simi" >Se encontraron pacientes GUARDADOS que coinciden con los datos CAPTURADOS. dele click para compararlos<br>';
				for(var c=0;c<obj.length; c++)
				{
					var prod1 = encodeURIComponent(no+'°'+pa+'°'+ma+'°'+$("#lnacimientoT").val()+'°'+cu+'°'+$("input[type='radio'][name='sexo']:checked").val()+'°'+$("#sangre option:selected").text()+'°'+fn+'°'+$("#nacionalidad option:selected").text());
					
					var prod2 = encodeURIComponent(ct+'°'+$("#nombreT").val()+'°'+$("#paternoT").val()+'°'+$("#maternoT").val()+'°'+$("input[type='radio'][name='sexoT']:checked").val()+'°'+$("#telefonoT").val()+'°'+$("#celularT").val()+'°'+$("#companiaT option:selected").text());
					
					var prod3 = encodeURIComponent($("#calle").val()+'°'+$("#numero").val()+'°'+$("#referencia").val()+'°'+$("#colonia").val()+'°'+$("#cp").val()+'°'+$("#ageb").val()+'°'+$("#sector").val()+'°'+$("#manzana").val()+'°'+$("#localidadT").val()+'°'+$("#telefono").val()+'°'+$("#celular").val()+'°'+$("#compania option:selected").text());
					 
					
					var url='/tes/enrolamiento/comparar_view/'+obj[c]["id"]+'/'+prod1+'/'+prod2+'/'+prod3
					campo+='<a href="'+url+'" style="padding:5px" class="btn btn-small btn-primary" id="similar">'+obj[c]["nombre"]+' '+obj[c]["total"]+'%</a>&nbsp;&nbsp;';
				}
				campo+="</span>";
				$("#tienesimilar").append(campo);
				$("#tienesimilar").attr("class","warning");
				$("a#similar").fancybox({
					'width'             : '90%',
					'height'            : '90%',				
					'transitionIn'	: 'elastic',
					'transitionOut'	: 'elastic',
					'type'			: 'iframe',
				});
			}
		});
	}
}