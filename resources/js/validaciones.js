﻿/* Funcion: solo_letras
 * NOTA.- Este archivo debe estar guardado con la codificación 'UTF-8' o 'ANSI as UTF-8' y no como 'ANSI' 
 * para que javascript pueda comparar los caracteres codificados de la página web con 'charset=utf-8'.
 */

function validar(e,op,id) // validacion de datos de entrada  e=event op=tipo cadena=value
{ 
    tecla = (document.all) ? e.keyCode : e.which; 
	
    if (tecla==8) return true; 
	if (tecla==0) return true; 
	
	if(op=="N")  //solo numeros
    patron =/[0-9]/; 
	
	if(op=="NS") //numero con signo
    patron =/[0-9-+]/; 

	if(op=="L") //solo letras
    patron =/[A-Za-z Ññ]/; 
	
	if(op=="LE") //caracteres y letras
    patron =/[A-Za-zÑñ0-9.:,;()&%$#-_@°|!?¡¿ ]/; 

	if(op=="M") // numeros decimales solo acepta un punto
	{
		var cadena=document.getElementById(id).value;
		if(/[.]/.test(cadena.substr(0,(cadena.length))))
		patron =/[0-9]/;
		else
		patron =/[0-9.]/;
	}
		
	if(op=="F") //fecha separador /
	{
		var cadena=""; var tamano=0;
    	cadena=document.getElementById(id).value;
		tamano=document.getElementById(id).value.length;
		
		if(tamano==2)
		{
			document.getElementById(id).value=cadena+"/";
		}
		if(tamano==5)
		{
			document.getElementById(id).value=cadena+"/";
		}
		
		patron =/[0-9/]/;
		
	}
	
	if(op=="NL") //numero con letras
    patron =/[A-Za-z0-9, ]/; 

	te = String.fromCharCode(tecla); 
    return patron.test(te); 
}

function mayuscula(dcmt,frmObj) // escribe mayusculas despues de un espacio-- Ejemplo De Como Seria
{
	var index;
	var tmpStr;
	var tmpChar;
	var preString;
	var postString;
	var strlen;
	tmpStr = dcmt.getElementById(frmObj).value;
	
	strLen = tmpStr.length;
	if (strLen > 0)  
	{
	for (index = 0; index < strLen; index++)  
	{
		if (index == 0)  
		{
		tmpChar = tmpStr.substring(0,1).toUpperCase();
		postString = tmpStr.substring(1,strLen);
		tmpStr = tmpChar + postString;
		}
		else 
		{
			tmpChar = tmpStr.substring(index, index+1);
			if (tmpChar == " " && index < (strLen-1))  
			{
				tmpChar = tmpStr.substring(index+1, index+2).toUpperCase();
				preString = tmpStr.substring(0, index+1);
				postString = tmpStr.substring(index+2,strLen);
				tmpStr = preString + tmpChar + postString;
			 }
		  }
	   }
	}
	dcmt.getElementById(frmObj).value = tmpStr;
}
function abrirpagina(pagina,tipo,w,h)  //para abrir paginas tipo popup=ventana y en la misma pagina aqui w=ancho h=alto
{
	if(tipo=="ventana")
	{
		var newWindow=window.open(pagina, '_blank', 'width='+w+',height='+h+',scrollbars=yes,resizable=yes,location=no,status=yes,toolbar=no,menubar=no,modal=yes');
		newWindow.focus();
	}
	else if(tipo=="aqui")
	{
		if(document.getElementById("contenido"))
		{
			$('div#contenido').html('<div class="info">Cargando... Espere &nbsp;  <img src="images/progressAnimation.gif" width="99" height="15" /></div>');
			$.ajax({
				url : pagina,
				success : function (data)
				{
					location.href=paina;
				}
			});
			e.preventDefault();
		}
		else
		window.location.href=pagina;
	}
	return false;
}

function mandarimprimir(dct,parte,stilo)  //se agrega un div de lo  que se quiere imprimir id='popimpr'
{
	var ficha = dct.getElementById(parte);
	var ventimp = window.open(' ', 'popimpr');
	ventimp.document.write(stilo+ficha.innerHTML );
	ventimp.document.close();
	ventimp.print();
	ventimp.close();
	return false;
}

function NumFormat(amount) 
{
	var val = parseFloat(amount);
	if (isNaN(val)) { return "0.00"; }
	if (val <= 0) { return "0.00"; }
	val += "";
	if (val.indexOf('.') == -1) { return val+".00"; }
	else { val = val.substring(0,val.indexOf('.')+3); }
	val = (val == Math.floor(val)) ? val + '.00' : ((val*10 == Math.floor(val*10)) ? val + '0' : val);
	return val;
} 

function getCookie(name) //obtiene el valor de una cookie
{
  var cname = name + "=";               
  var dc = document.cookie;             
  if (dc.length > 0) {              
    begin = dc.indexOf(cname);       
    if (begin != -1) {           
      begin += cname.length;       
      end = dc.indexOf(";", begin);
      if (end == -1) end = dc.length;
        return unescape(dc.substring(begin, end));
    } 
  }
  return null;
}



/**Funcion estándard para AJAX**/
function getXHTTP( ) {
  var xhttp;
   try { 
      xhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e2) {
 		 // This block handles Mozilla/Firefox browsers...
	    try {
	      xhttp = new XMLHttpRequest();
	    } catch (e3) {
	      xhttp = false;
	    }
      }
    }
  return xhttp;
}

/**Funcion estándard para AJAX**/
var http = getXHTTP();

// inicio validar formularios
function validarFormulario(formulario)//para este metodo los inputs agregar title='requiere' y  llamar en el boton o submit asi                                              click="return buscarpagina('formulario')"  formulario=nombre del formulario
{
		bien =true;
		document.getElementById("enviandoof").style.display="none";
		document.getElementById("enviandoon").style.display="";
        var c=0;var frm=document.getElementById(formulario);  //formulario
        for(c=0;c<frm.length;c++)//tantos imputs tenga el formulario
        {
			if(frm.elements[c].title=="requiere"&&(frm.elements[c].value==""))//si el campo es requerido y esta vacio
			{
				if(bien)
				frm.elements[c].focus(); 
				frm.elements[c].style.borderColor="#FF0000";
				bien= false;
			} //cambiar el estilo
        }
        if(bien==false)//si bien cambio
        {
			document.getElementById("enviandoof").style.display="";
			document.getElementById("enviandoon").style.display="none";
			if(document.getElementById("alert"))
			{
				document.getElementById("alert").className="warning";
				document.getElementById("alert").innerHTML='<div>Rellene los campos en rojo</div>';
			}
			else
			alert("Rellene los campos en rojo");
        }
        return bien;
}

function limpiaformulario(formulario) //va en el evento keyup formulario onkeyup="limpiarerror(this.id)"
{	
	var c=0;var frm=document.getElementById(formulario);
	for(c=0;c<frm.length;c++)
	{
		if(frm.elements[c].value!="")
			frm.elements[c].style.borderColor="";
	}
	if(document.getElementById("alert"))
	{
		document.getElementById("alert").innerHTML="";
		document.getElementById("alert").className="";
	}
}
var cx=0;
function entertab(e) //convertir el enter en un tab
{ 
	if (e.keyCode == 13) 
	{			
		cx++;
		if(!document.forms[0].elements[cx])cx=0;
		document.forms[0].elements[cx].focus();  
		return false;
   }
}
// fin validar formularios