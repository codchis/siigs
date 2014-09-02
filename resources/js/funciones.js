var objFecha = new Date();

var optionsFecha = {
    changeMonth: false,
    changeYear: false,
    navigationAsDateFormat: true,
    duration: "fast",
    dateFormat: 'dd-mm-yy',
    constrainInput: true,
    firstDay: 0,
    closeText: 'X',
    showOn: 'both',
    buttonImage: '/resources/images/calendar.gif',
    buttonImageOnly: true,
    buttonText: 'Clic para seleccionar una fecha',
    yearRange: '2005:'+objFecha.getFullYear(),
    showButtonPanel: false,
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
    beforeShow: function(dateText, inst) {
        $('#'+inst.id).mask("99-99-9999");
        $('#'+inst.id).removeClass('errorInput');
 
    },
    onClose: function(dateText, inst) {
        if(dateText != '__-__-____' && dateText != '') {
            if(!isDate(dateText)) {
                $('#'+inst.id).addClass('errorInput');
            } else {
                $('#alert').html('');
				$('#alert').css('display','');
                $('#'+inst.id).removeClass('errorInput');
				$('#alert').removeClass('warning');
            }
        }
        
        $('#'+inst.id).closest("form").submit(function(event){
            if( $(this).find('input.errorInput').length != 0) {
                showAlerta({mensaje: 'La fecha es incorrecta'});
                event.preventDefault();
                return false;
            }
                
        });
    },
};

$(document).ready(function() {
    $("#ayuda")
        .fancybox({
            'width'         : '50%',
            'height'        : '60%',				
            'transitionIn'	: 'elastic',
            'transitionOut'	: 'elastic',
            'type'			: 'iframe',	
            onComplete: function(){
                $('#fancybox-frame').load(function(){
                    $.fancybox.hideActivity();
                });
            }
        });
        
    $("#ayuda").click(function(e) { $.fancybox.showActivity(); });
});

function showAlerta(parametros) {
    if(typeof(parametros.alert) == 'undefined' || parametros.alert == null){
        if($('#alert').length == 0) {
            $('.contenido').prepend('<div id="alert" class="warning" style="display: none;"></div>');
        }
    }
    
    alert = (parametros.alert) ? parametros.alert : '#alert';
    $("html,body").animate({ scrollTop: $(alert).offset().top}, 1000 );
    
    if(!$(alert).hasClass('warning'))
        $(alert).addClass('warning');
    
    $(alert).html(parametros.mensaje).fadeIn(2000, function(){ setTimeout(function(){ $(alert).fadeOut(2000); }, 1000); });
}

// funcion dependiente de jquery.flot
function dibujaGrafica(datos, etiquetasEjes, idDiv) {
    $grafica = $.plot("#"+idDiv, datos, {
        xaxes: [{
            axisLabel: etiquetasEjes.xaxes,
            axisLabelPadding: 20
        }],
        yaxes: [{
            axisLabel: etiquetasEjes.yaxes,
            axisLabelPadding: 40
        }],
        legend: {
            position: "nw"
        },
        series: {
            lines: {
                show: true
            },
            points: {
                show: false
            }
        },
        grid: {
            show: true,
            hoverable: true,
            clickable: true,
            margin: {
                left: -10,
                bottom: 0
            },
            labelMargin: 22,
            borderColor: "#000",
            backgroundColor: { colors: ["#fff", "#fff"] }
        }
    });
    
    if($("#tooltip-flot").length == 0) {
        $("<div id='tooltip-flot'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "2px",
            "background-color": "#fee",
            opacity: 0.80
        }).appendTo("body");
    }

    $("#"+idDiv).bind("plothover", function (event, pos, item) {
        if (item) {
            var x = item.datapoint[0].toFixed(2),
                y = item.datapoint[1].toFixed(2);
            
            var xaxisLabel = $(this).find('.xaxisLabel').text(),
                yaxisLabel = $(this).find('.yaxisLabel').text();
            
            // Obtiene los datos que estan dentro del parentesis
            xaxisLabel = xaxisLabel.substring(xaxisLabel.indexOf('(')+1, xaxisLabel.indexOf(')'));
            yaxisLabel = yaxisLabel.substring(yaxisLabel.indexOf('(')+1, yaxisLabel.indexOf(')'));

            $("#tooltip-flot").html("(" + x + " " + xaxisLabel + ", " + y + " " + yaxisLabel + ")")
                .css({top: item.pageY+5, left: item.pageX+5})
                .fadeIn(200);
        } else {
            $("#tooltip-flot").hide();
        }
    });
    
    return $grafica.getCanvas();
}