/* Funcion: solo_letras
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
    patron =/[A-Za-z Ññ áÁéÉíÍóÓúÚüÜ ]/; 
	
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
	if(stilo=="")
	{
		stilo="<style type='text/css'>table {max-width: 100%;background-color: transparent;}th {text-align: left;}.table {width: 100%;margin-bottom: 20px;}.table > thead > tr > th,.table > tbody > tr > th,.table > tfoot > tr > th,.table > thead > tr > td,.table > tbody > tr > td,.table > tfoot > tr > td {padding: 8px;line-height: 1.428571429;vertical-align: top;border-top: 1px solid #dddddd;}.table > thead > tr > th {vertical-align: bottom;border-bottom: 2px solid #dddddd;}.table > caption + thead > tr:first-child > th,.table > colgroup + thead > tr:first-child > th,.table > thead:first-child > tr:first-child > th,.table > caption + thead > tr:first-child > td,.table > colgroup + thead > tr:first-child > td,.table > thead:first-child > tr:first-child > td {border-top: 0;}.table > tbody + tbody {border-top: 2px solid #dddddd;}.table .table {background-color: #ffffff;}.table-condensed > thead > tr > th,.table-condensed > tbody > tr > th,.table-condensed > tfoot > tr > th,.table-condensed > thead > tr > td,.table-condensed > tbody > tr > td,.table-condensed > tfoot > tr > td {padding: 5px;}.table-bordered {border: 1px solid #dddddd;}.table-bordered > thead > tr > th,.table-bordered > tbody > tr > th,.table-bordered > tfoot > tr > th,.table-bordered > thead > tr > td,.table-bordered > tbody > tr > td,.table-bordered > tfoot > tr > td {border: 1px solid #dddddd;}.table-bordered > thead > tr > th,.table-bordered > thead > tr > td {border-bottom-width: 2px;}.table-striped > tbody > tr:nth-child(odd) > td,.table-striped > tbody > tr:nth-child(odd) > th {background-color: #f9f9f9;}.table-hover > tbody > tr:hover > td,.table-hover > tbody > tr:hover > th {background-color: #f5f5f5;}table col[class*='col-'] {position: static;float: none;display: table-column;}table td[class*='col-'],table th[class*='col-'] {float: none;display: table-cell;}.table > thead > tr > .active,.table > tbody > tr > .active,.table > tfoot > tr > .active,.table > thead > .active > td,.table > tbody > .active > td,.table > tfoot > .active > td,.table > thead > .active > th,.table > tbody > .active > th,.table > tfoot > .active > th {background-color: #f5f5f5;}.table-hover > tbody > tr > .active:hover,.table-hover > tbody > .active:hover > td,.table-hover > tbody > .active:hover > th {background-color: #e8e8e8;}.table > thead > tr > .success,.table > tbody > tr > .success,.table > tfoot > tr > .success,.table > thead > .success > td,.table > tbody > .success > td,.table > tfoot > .success > td,.table > thead > .success > th,.table > tbody > .success > th,.table > tfoot > .success > th {background-color: #dff0d8;}.table-hover > tbody > tr > .success:hover,.table-hover > tbody > .success:hover > td,.table-hover > tbody > .success:hover > th {background-color: #d0e9c6;}.table > thead > tr > .danger,.table > tbody > tr > .danger,.table > tfoot > tr > .danger,.table > thead > .danger > td,.table > tbody > .danger > td,.table > tfoot > .danger > td,.table > thead > .danger > th,.table > tbody > .danger > th,.table > tfoot > .danger > th {background-color: #f2dede;}.table-hover > tbody > tr > .danger:hover,.table-hover > tbody > .danger:hover > td,.table-hover > tbody > .danger:hover > th {background-color: #ebcccc;}.table > thead > tr > .warning,.table > tbody > tr > .warning,.table > tfoot > tr > .warning,.table > thead > .warning > td,.table > tbody > .warning > td,.table > tfoot > .warning > td,.table > thead > .warning > th,.table > tbody > .warning > th,.table > tfoot > .warning > th {background-color: #fcf8e3;}.table-hover > tbody > tr > .warning:hover,.table-hover > tbody > .warning:hover > td,.table-hover > tbody > .warning:hover > th {background-color: #faf2cc;}@media (max-width: 767px) {.table-responsive {  width: 100%;  margin-bottom: 15px;  overflow-y: hidden;  overflow-x: scroll;  -ms-overflow-style: -ms-autohiding-scrollbar;  border: 1px solid #dddddd;  -webkit-overflow-scrolling: touch;}.table-responsive > .table {  margin-bottom: 0;}.table-responsive > .table > thead > tr > th,.table-responsive > .table > tbody > tr > th,.table-responsive > .table > tfoot > tr > th,.table-responsive > .table > thead > tr > td,.table-responsive > .table > tbody > tr > td,.table-responsive > .table > tfoot > tr > td {  white-space: nowrap;}.table-responsive > .table-bordered {  border: 0;}.table-responsive > .table-bordered > thead > tr > th:first-child,.table-responsive > .table-bordered > tbody > tr > th:first-child,.table-responsive > .table-bordered > tfoot > tr > th:first-child,.table-responsive > .table-bordered > thead > tr > td:first-child,.table-responsive > .table-bordered > tbody > tr > td:first-child,.table-responsive > .table-bordered > tfoot > tr > td:first-child {  border-left: 0;}.table-responsive > .table-bordered > thead > tr > th:last-child,.table-responsive > .table-bordered > tbody > tr > th:last-child,.table-responsive > .table-bordered > tfoot > tr > th:last-child,.table-responsive > .table-bordered > thead > tr > td:last-child,.table-responsive > .table-bordered > tbody > tr > td:last-child,.table-responsive > .table-bordered > tfoot > tr > td:last-child {  border-right: 0;}.table-responsive > .table-bordered > tbody > tr:last-child > th,.table-responsive > .table-bordered > tfoot > tr:last-child > th,.table-responsive > .table-bordered > tbody > tr:last-child > td,.table-responsive > .table-bordered > tfoot > tr:last-child > td {  border-bottom: 0;}}h2{font-size: 14.5px;}</style>";
	}
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
var clk=0;
// inicio validar formularios
function validarFormulario(formulario)//para este metodo los inputs agregar title='requiere' y  llamar en el boton o submit asi                                              onclick="return validarFormulario('formulario')"  formulario=nombre del formulario
{
		bien =true; clk=1;
        var c=0;var frm=document.getElementById(formulario);  //formulario
        for(c=0;c<frm.length;c++)//tantos imputs tenga el formulario
        {
			if(frm.elements[c].title=="requiere"&&(frm.elements[c].value==""))//si el campo es requerido y esta vacio
			{
				if(bien)
				frm.elements[c].focus(); 
				frm.elements[c].style.borderColor="#FF0000";
				frm.elements[c].className="requiere";
				bien= false;
			} //cambiar el estilo
        }
        if(bien==false)//si bien cambio
        {
			if(document.getElementById("alert"))
			{
				document.getElementById("alert").className="warning";
				document.getElementById("alert").innerHTML='<div>Los campos marcados en rojo son obligatorios</div>';
			}
			else
			alert("Los campos marcados en rojo son obligatorios");
        }
        return bien;
}

function limpiaformulario(formulario) //va en el evento keyup formulario onkeyup="limpiaformulario(this.id)"
{	
	var c=0;var frm=document.getElementById(formulario);
	for(c=0;c<frm.length;c++)
	{
		if(frm.elements[c].value!="")
		{
			frm.elements[c].style.borderColor="";
			if(frm.elements[c].type!="submit"&&frm.elements[c].type!="button")
			frm.elements[c].className="norequiere";
		}
		else if(frm.elements[c].title=="requiere"&&(frm.elements[c].value==""))
			frm.elements[c].className="requiere";
	}
	if(document.getElementById("alert")&&clk==0)
	{
		document.getElementById("alert").innerHTML="";
		document.getElementById("alert").className="";
	}
	clk=0;
}
function obligatorios(formulario)
{
	var c=0;var frm=document.getElementById(formulario);  //formulario
	for(c=0;c<frm.length;c++)//tantos imputs tenga el formulario
	{
		if(frm.elements[c].title=="requiere"&&(frm.elements[c].value==""))//si el campo es requerido y esta vacio
		{
			frm.elements[c].className="requiere";
		} //cambiar el estilo
	}
}
var cx=0;
function entertab(e,i) //convertir el enter en un tab
{ 
	if (e.keyCode == 13||e.keyCode == 9) 
	{		
		if(i==12||i==25||i==38)
			Accordion1.openNextPanel();
		if(document.getElementById("fechaT"))
			i=i+2;	
		document.forms[0].elements[i].focus();
		return false;
	}
}
// fin validar formularios

function download(strData, strFileName, strMimeType) {
	var D = document,
		a = D.createElement("a");
		strMimeType= strMimeType || "application/octet-stream";

	if (window.MSBlobBuilder) { //IE10+ routine
		var bb = new MSBlobBuilder();
		bb.append(strData);
		return navigator.msSaveBlob(bb, strFileName);
	} /* end if(window.MSBlobBuilder) */


	if ('download' in a) { 
		a.href = "data:" + strMimeType + "," + encodeURIComponent(strData);
		a.setAttribute("download", strFileName);
		a.innerHTML = "downloading...";
		D.body.appendChild(a);
		setTimeout(function() {
			a.click();
			D.body.removeChild(a);
		}, 333);
		return true;
	} 

	var f = D.createElement("iframe");
	D.body.appendChild(f);
	f.src = "data:" +  strMimeType   + "," + encodeURIComponent(strData);

	setTimeout(function() {
		D.body.removeChild(f);
	}, 333);
	return true;
}

jQuery.fn.table2CSV = function(options) {
    var options = jQuery.extend({
        separator: ',',
        header: [],
        delivery: 'popup' // popup, value
    },
    options);

    var csvData = [];
    var headerArr = [];
    var el = this;

    //header
    var numCols = options.header.length;
    var tmpRow = []; // construct header avalible array

    if (numCols > 0) {
        for (var i = 0; i < numCols; i++) {
            tmpRow[tmpRow.length] = formatData(options.header[i]);
        }
    } else {
        $(el).filter(':visible').find('th').each(function() {
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
        });
    }

    row2CSV(tmpRow);

    // actual data
    $(el).find('tr').each(function() {
        var tmpRow = [];
        $(this).filter(':visible').find('td').each(function() {
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
        });
        row2CSV(tmpRow);
    });
    if (options.delivery == 'popup') {
        var mydata = csvData.join('\n');
        return popup(mydata);
    } else {
        var mydata = csvData.join('\n');
        return mydata;
    }

    function row2CSV(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(options.separator);
            csvData[csvData.length] = mystr;
        }
    }
    function formatData(input) {
        // replace " with â€œ
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "â€œ");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return '"' + output + '"';
    }
    function popup(data) {
        /*var generator = window.open('', 'csv', 'height=400,width=600');
        generator.document.write('<html><head><title>CSV</title>');
        generator.document.write('</head><body >');
        generator.document.write('<textArea cols=70 rows=15 wrap="off" >');
        generator.document.write(data);
        generator.document.write('</textArea>');
        generator.document.write('</body></html>');
        generator.document.close();
        */
		
		return data;
    }
};