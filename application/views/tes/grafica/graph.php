<?php
$json = json_encode($array);
$array = json_decode($json, true);

$a=0;$var="";$push="";
for($x=1;$x<count($array[0])+1;$x++)
{
	$var.="d$x=[";
	for($i=0;$i<count($array);$i++)
	{
		if(strlen($array[$i]["d$x"])>3)
		{
			$var.=$array[$i]["d$x"];	
			if(($i+1)<count($array))
			$var.=",";
		}
	}
	$var.="],";
}
$dat="";
foreach($etiqueta as $item)
{
	$a++;
	$dat.="{ data : d$a, label : '$item' },";
}
$dat=substr($dat,0,strlen($dat)-1);	
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="" />
    <link rel="icon" type="image/gif" href="/resources/grafica/images/favicon.gif" />
    <link rel="stylesheet" type="text/css" href="/resources/grafica/css/hsd.css?d3fa1" />
    <link rel="stylesheet" type="text/css" href="/resources/grafica/css/hsd-flotr2.css?d3fa1" />
</head>

<body>
<div id="body-container" class="flotr2-index">
<div id="header-container"><div id="header"><?php echo urldecode($titulo); ?></div></div>
<div id="links-container">
  <div id="links">
    <div id="links-footer"></div>
  </div>
</div>
<div id="content-container">
    <div id="content">
        <div id="examples"></div>
    </div>
</div>

<script src="/resources/grafica/js/hsd.js?d3fa1"></script>
<script src="/resources/grafica/js/hsd-flotr2.js?d3fa1"></script>
<script type="text/javascript">
(function($,e,b)
{
	var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);
	function a(j)
	{
		j=j||location.href;
		return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")
	}
	$.fn[c]=function(j)
	{
		return j?this.bind(c,j):this.trigger(c)};
		$.fn[c].delay=50;g[c]=$.extend(g[c]
		,{setup:function()
		{
			if(d)
			{
				return false
			}
			$(f.start)
		}
		,teardown:function()
		{
			if(d)
			{
				return false
			}
			$(f.stop)
		}
	});
	f=(function()
	{
		var j={}
		,p
		,m=a()
		,k=function(q)
		{
			return q
		}
		,l=k
		,o=k;
		j.start=function()
		{
			p||n()
		};
		j.stop=function()
		{
			p&&clearTimeout(p);
			p=b
		};
		function n()
		{
			var r=a()
			,q=o(m);
			if(r!==m)
			{
				l(m=r,q);
				$(e).trigger(c)
			}
			else
			{
				if(q!==m)
				{
					location.href=location.href.replace(/#.*/,"")+q
				}
			}
			p=setTimeout(n,$.fn[c].delay)
		}
		$.browser.msie&&!d&&(
		function()
		{
			var q,r;j.start=function()
			{
				if(!q)
				{
					r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function()
					{
						r||l(a());n()
					}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=
					function()
					{
						try
						{
							if(event.propertyName==="title")
							{
								q.document.title=h.title
							}
						}
						catch(s){}
					}
				}
			};
			j.stop=k;o=
			function()
			{
				return a(q.location.href)
			}
			;l=function(v,s)
			{
				var u=q.document,t=$.fn[c].domain;if(v!==s)
				{
					u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');
					u.close();q.location.hash=v
				}
			}
		})();
		return j
	})()
})(jQuery,this);
	

(function () {

var ExampleList = function () {

  // Map of examples.
  this.examples = {};

};

ExampleList.prototype = {

  add : function (example) {
    this.examples[example.key] = example;
  },

  get : function (key) {
    return key ? (this.examples[key] || null) : this.examples;
  },

  getType : function (type) {
    return Flotr._.select(this.examples, function (example) {
      return (example.type === type);
    });
  }
}

Flotr.ExampleList = new ExampleList();

})();


(function () {

 Flotr.ExampleList.add({
  key : 'time',
  name : 'Time',
  callback : time,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});
function time (container) {
  var
    <?php echo $var?> 
    start = new Date("<?php echo date("Y/m/d",strtotime($nacimiento)); ?>").getTime(),
    options,
    graph,
    i, x, o;
/*
  for (i = 0; i < 60; i++) 
  {
    x = start+(i*1000*3600*24*36.5);
    d1.push([x, i+Math.random()*30+Math.sin(i/20+Math.random()*2)*20+Math.sin(i/10+Math.random())*10]);
	x = start+(i*1000*3600*24*36.5);
	d2.push([x, i+Math.random()*30+Math.sin(i/20+Math.random()*2)*20+Math.sin(i/10+Math.random())*10]);
	x = start+(i*1000*3600*24*36.5);
	d3.push([x, i+Math.random()*30+Math.sin(i/20+Math.random()*2)*20+Math.sin(i/10+Math.random())*10]);
  }
     */  
  options = 
  {
    xaxis : {
      mode : 'time', 
      labelsAngle : 45
    },
    selection : {
      mode : 'x'
    },
    HtmlText : false,
    title : 'Fecha'
  };
  function drawGraph (opts) {

    // Clone the options, so the 'options' variable always keeps intact.
    o = Flotr._.extend(Flotr._.clone(options), opts || {});

    // Return a new graph.
    return Flotr.draw(
      container,
      [  <?php echo $dat;?> ],
      o
    );
  }      

  graph = drawGraph();      
        
  Flotr.EventAdapter.observe(container, 'flotr:select', function(area)
  {
    // Draw selected area
    graph = drawGraph({
      xaxis : { min : area.x1, max : area.x2, mode : 'time', labelsAngle : 45 },
      yaxis : { min : area.y1, max : area.y2 }
    });
  });
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
  Flotr.EventAdapter.observe(container, 'flotr:click', function () { graph = drawGraph(); });
}

})();


(function () 
{

Flotr.ExampleList.add({
  key : 'basic',
  name : 'Basic',
  callback : basic,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});
function basic (container) {

   var <?php echo $var?>                              
    i, graph;

  // Draw Graph
  graph = Flotr.draw(container, [
    <?php echo $dat;?>
  ], {
    xaxis: {
      minorTickFreq: 10
    },
	 
    grid: {
      minorVerticalLines: true
    }
  
	 ,title : 'Cuadriculada'
	 ,mouse : {
        track           : true, // Enable mouse tracking
        lineColor       : 'purple',
        relative        : true,
        position        : 'ne',
        sensibility     : 1,
        trackDecimals   : 2,
        trackFormatter  : function (o) { return 'x = ' + o.x +', y = ' + o.y; }
      },
      crosshair : {
        mode : 'xy'
      }
  });
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
  
}

})();


(function () {

Flotr.ExampleList.add({
  key : 'stepped',
  name : 'Stepped',
  callback : stepped,
  type : 'test',
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

function stepped (container) {
  var <?php echo $var?>  
    i, graph;

  // Draw Graph
  graph = Flotr.draw(container, [ <?php echo $dat;?> ], {
	  
    lines: {
      steps : true,
      show : true
    },
    xaxis: {
      minorTickFreq: 4
    }, 
    yaxis: {
      autoscale: true
    },
    grid: {
      minorVerticalLines: true
    },
    mouse : {
      track : true,
      relative : true
    }
  });
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
}

})();

(function () {

Flotr.ExampleList.add({
  key : 'axis',
  name : 'Axis',
  callback : axis,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

function axis (container) 
{

  var <?php echo $var?>                     
    graph;
                

  graph = Flotr.draw(container, [ 
      <?php echo $dat;?>       
    ], {
      xaxis : {
        noTicks : 7,              // Display 7 ticks.
      },
      grid : {
        verticalLines : false,
        backgroundColor : {
          colors : [[0,'#fff'], [1,'#ccc']],
          start : 'top',
          end : 'bottom'
        }
      },
      legend : {
        position : 'nw'
      }
      ,title : 'Plano'
	 ,mouse : {
        track           : true, // Enable mouse tracking
        lineColor       : 'purple',
        relative        : true,
        position        : 'ne',
        sensibility     : 1,
        trackDecimals   : 2,
        trackFormatter  : function (o) { return 'x = ' + o.x +', y = ' + o.y; }
      },
      crosshair : {
        mode : 'xy'
      }
  });
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
  
}

})();


(function () {

Flotr.ExampleList.add({
  key : 'bars',
  name : 'Barras',
  callback : bars,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

Flotr.ExampleList.add({
  key : 'bars-h',
  name : 'Horizontales',
  args : [true],
  callback : bars,
  tolerance : 5,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

function bars (container, horizontal) {

  var
    horizontal = (horizontal ? true : false), // Show horizontal bars
    <?php echo $var?>   
    point,graph ,                                    // Data point variable declaration
    i;

              
  // Draw the graph
  graph =Flotr.draw(
    container,
    [
    <?php echo $dat?>
  ],
    {
      bars : {
        show : true,
        horizontal : horizontal,
        shadowSize : 0,
        barWidth : 0.5
      },
      mouse : {
        track : true,
        relative : true
      },
      yaxis : {
        min : 0,
        autoscaleMargin : 1
      },title : 'Barras'
    }
  );
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
}

})();

(function () {

Flotr.ExampleList.add({
  key : 'stacked',
  name : 'Stacked',
  callback : stacked,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

Flotr.ExampleList.add({
  key : 'horizontal',
  name : 'Horizontal',
  args : [true],
  callback : stacked,
  tolerance : 5,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

function stacked (container, horizontal) {

  var <?php echo $var?>  
    graph, i;


  graph = Flotr.draw(container,[
    <?php echo $dat?>
  ], {
    legend : {
      backgroundColor : '#D2E8FF' // Light blue 
    },
    bars : {
      show : true,
      stacked : true,
      horizontal : horizontal,
      barWidth : 0.6,
      lineWidth : 1,
      shadowSize : 0
    },
    grid : {
      verticalLines : horizontal,
      horizontalLines : !horizontal
    },title : 'Barras'
  });
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
}

})();



(function () {

Flotr.ExampleList.add({
  key : 'bubble',
  name : 'Bubble',
  callback : bubble,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

function bubble (container) {

  <?php echo $var?>  
    point, graph, i;
      
  
  // Draw the graph
  graph = Flotr.draw(container, [<?php echo $dat?>], {
    bubbles : { show : true, baseRadius : 5 },
    xaxis   : { min : -4, max : 14 },
    yaxis   : { min : -4, max : 14 }
  });
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
}

})();
(function () {

Flotr.ExampleList.add({
  key : 'pie',
  name : 'Pie',
  callback : pie,
  description : '' + 
    '<form name="image-download" id="image-download" action="" onsubmit="return false">' +
      '<label><input type="radio" name="format" value="png" checked="checked" /> PNG</label>' +
      '<label><input type="radio" name="format" value="jpeg" /> JPEG</label>' +

      '<button name="to-image" onclick="CurrentExample(\'to-image\')">Actualizar</button>' +
      '<button name="download" onclick="CurrentExample(\'download\')">Descargar</button>' +
      '<button name="reset" onclick="CurrentExample(\'reset\')">Reset</button>' +
    '</form>'
});

function pie (container) {

  var
    <?php echo $var; ?>
    graph;
  
  graph = Flotr.draw(container, [
    <?php echo substr($dat,0,strlen($dat)-2)?>,
      pie : {
        explode : 50
      },
	  title : 'pie',
      subtitle : 'pie'
    },
   
  ], {
    HtmlText : false,
    grid : {
      verticalLines : false,
      horizontalLines : false
    },
    xaxis : { showLabels : false },
    yaxis : { showLabels : false },
    pie : {
      show : true, 
      explode : 6
    },
    mouse : { track : true },
    legend : {
      position : 'se',
      backgroundColor : '#D2E8FF'
    },title : 'Pie'
  });
  
  this.CurrentExample = function (operation) {

    var
      format = $('#image-download input:radio[name=format]:checked').val();
    if (Flotr.isIE && Flotr.isIE < 9) {
      alert(
        "Your browser doesn't allow you to get a bitmap image from the plot, " +
        "you can only get a VML image that you can use in Microsoft Office.<br />"
      );
    }

    if (operation == 'to-image') {
      graph.download.saveImage(format, null, null, true)
    } else if (operation == 'download') {
      graph.download.saveImage(format);
    } else if (operation == 'reset') {
      graph.download.restoreCanvas();
    }
  };
}

})();


(function()
{
	var a=Flotr.EventAdapter
	,b=Flotr._
	,c="click"
	,d="example"
	,e="mouseenter"
	,f="mouseleave"
	,g="."
	,h="flotr-examples"
	,i="flotr-examples-container"
	,j="flotr-examples-reset"
	,k="flotr-examples-thumbs"
	,l="flotr-examples-thumb"
	,m="flotr-examples-collapsed"
	,n="flotr-examples-highlight"
	,o="flotr-examples-large"
	,p="flotr-examples-medium"
	,q="flotr-examples-small"
	,r="flotr-examples-mobile"
	,s='<div class="'+l+'"></div>'
	,t='<div class="'+h+'">'+'<div class="'+j+'">Ver Todos</div>'+'<div class="'+k+'"></div>'+'<div class="'+i+'"></div>'+"</div>";
	Examples=function(a)
	{
		if(b.isUndefined(Flotr.ExampleList))
			throw"Flotr.ExampleList not defined.";
		this.options=a,this.list=Flotr.ExampleList
		,this.current=null
		,this.single=!1
		,this._initNodes()
		,this._example=new Flotr.Examples.Example({node:this._exampleNode})
		,this._initExamples()
	}
	,Examples.prototype={examples:function(){function f(b)
	{
		var c=$(b.currentTarget)
		,e=c.data("example")
		,f=b.data.orientation;f^c.hasClass(n)&&(c.toggleClass(n).css(a),d._example.executeCallback(e,c))
	}
	
	var a={cursor:"pointer"}
	,b=this._thumbsNode
	,c=this.list.get()
	,d=this
	,e=[<?php echo $graficas; ?>];
	
	(function h()
	{
		var a=e.shift()
		,f=c[a];
		if(f.type==="profile"||f.type==="test")
			return;var g=$(s);
		g.data("example",f)
		,b.append(g)
		,d._example.executeCallback(f,g)
		,g.click(function(){d._loadExample(f)})
		,e.length&&setTimeout(h,20)})()
		,b.delegate(g+l,"mouseenter",{orientation:!0},f)
		,b.delegate(g+l,"mouseleave",{orientation:!1},f),$(window).hashchange&&$(window).hashchange(function(){d._loadHash()})}
		,_loadExample:function(a)
		{
			if(a)
			{
				if(this._currentExample!==a)
					this._currentExample=a;
				else 
					return;
				window.location.hash="!"+(this.single?"single/":"")+a.key,u||(this._thumbsNode.css(
				{
					position:"absolute",height:"0px",overflow:"hidden",width:"0px"
				})
				,this._resetNode.css({top:"16px"}))
				,this._examplesNode.addClass(m)
				,this._exampleNode.show()
				,this._example.setExample(a)
				,this._resize()
				,$(document).scrollTop(0)}}
				,_reset:function(){window.location.hash="",u||this._thumbsNode.css({position:"",height:"",overflow:"",width:""})
				,this._examplesNode.removeClass(m)
				,this._thumbsNode.height("")
				,this._exampleNode.hide()}
				,_initNodes:function(){var a=$(this.options.node)
				,b=this
				,c=$(t);b._resetNode=c.find(g+j)
				,b._exampleNode=c.find(g+i)
				,b._thumbsNode=c.find(g+k)
				,b._examplesNode=c,b._resetNode.click(function(){b._reset()}),a.append(c),this._initResizer()}
				,_initResizer:function(){function e(){var b=c.height()-(a.options.thumbPadding||0)
				,e=c.width(),f;e>1760?(f=o,a._thumbsNode.height(b)):e>1140?(f=p,a._thumbsNode.height(b)):(f=q,a._thumbsNode.height(""))
				,d!==f&&(d&&a._examplesNode.removeClass(d)
				,a._examplesNode.addClass(f),d=f)
			}
			var 
			a=this
			,b=a._examplesNode
			,c=$(window)
			,d;$(window).resize(e)
			,e(),this._resize=e}
			,_initExamples:function(){var a=window.location.hash,b,c;a=a.substring(2)
			,c=a.split("/")
			,c.length==1?(this.examples()
			,a&&this._loadHash()):c[0]=="single"&&(this.single=!0,b=this.list.get(c[1]))}
			,_loadHash:function(){var a=window.location.hash,b;a=a.substring(2)
			,a?(b=this.list.get(a)
			,this._loadExample(b)):this._reset()
		}
	};
	var u=function()
	{
		var 
		a=!!(navigator.userAgent.match(/Android/i)
		||navigator.userAgent.match(/webOS/i)
		||navigator.userAgent.match(/iPhone/i)
		||navigator.userAgent.match(/iPod/i)),b=!!$.browser.mozilla;
		return!a||b
	}();
	Flotr.Examples=Examples})()
	,function()
	{
		var a=Flotr._
		,b="."
		,c="flotr-example"
		,d="flotr-example-label"
		,e="flotr-example-title"
		,f="flotr-example-description"
		,g="flotr-example-editor"
		,h="flotr-example-graph"
		,i='<div class="'+c+'">'+'<div class="'+d+" "+e+'"></div>'+'<div class="'+f+'"></div>'+'<div class="'+g+'"></div>'+"</div>"
		,j=function(a){this.options=a,this.example=null,this._initNodes()
	};
	j.prototype={setExample:function(a)
	{
		var b=this.getSource(a)
		,c=this._editorNode;this.example=a
		,Math.seedrandom(a.key)
		,this._exampleNode.css({display:"block"})
		,this._titleNode.html(a.name||"")
		,this._markupNode.html(a.description||"")
		,this._editor?this._editor.setExample(b,a.args):this._editor=new Flotr.Examples.Editor(c,{args:a.args,example:b,teardown:function()
		{
			Flotr.EventAdapter.stopObserving($(c).find(".render")[0])
			,$(c).find("canvas").each(function(a,b)
				{
					Flotr.EventAdapter.stopObserving(b)
				}
			)}
		})
	}
	,getSource:function(a)
	{
		var b=a.callback.toString();
		return navigator.userAgent.search(/firefox/i)!==-1&&(b=js_beautify(b)),b
	}
	,executeCallback:function(b,c)
	{
		a.isElement(c)||(c=c[0]);var d=b.args?[c].concat(b.args):[c];
		return Math.seedrandom(b.key),b.callback.apply(this,d)
	}
	,_initNodes:function()
	{
		var a=this.options.node
		,c=$(i);this._titleNode=c.find(b+e)
		,this._markupNode=c.find(b+f)
		,this._editorNode=c.find(b+g)
		,this._exampleNode=c
		,a.append(c)
	}
}
,Flotr.Examples.Example=j}()
,function()
{
	function Editor(a,b)
	{
		function o()
		{
			i.hide()
			,f&&f.call()
			,m.render({example:d,render:h})
		}
		function p(a,b,c)
		{
			var 
			d=!1
			,e='<span class="error">Error: </span>'
			,f,g;e+='<span class="message">'+a+"</span>"
			,typeof c!="undefined"&&(e+='<span class="position">'
			,e+='Line <span class="line">'+c+"</span>"
			,console.log(b),b&&(e+=" of "
			,b==window.location?(e+='<span class="url">script</span>',!d):e+='<span class="url">'+b+"</span>"),e+=".</span>")
			,i.show()
			,i.html(e)
		}
		var 
		c=b.type||"javascript"
		,d=b.example||""
		,e=b.noRun||!1
		,f=b.teardown||!1
		,g=$(T_CONTROLS)
		,h=$(T_RENDER)
		,i=$(T_ERRORS)
		,j=$(T_SOURCE)
		,k=$(T_EDITOR)
		,l="editor-render-"+COUNT
		,m
		,h
		,n;
		m=new TYPES[c]({onerror:p});
		if(!m)
			throw"Invalid type: API not found for type `"+c+"`.";
		h.attr("id",l)
		,i.hide()
		,k.append(h).append(g).append(j).addClass(c).addClass(e?"no-run":"")
		,a=$(a)
		,a.append(k)
		,j.append(i)
		,d=m.example({args:b.args,example:d,render:h})
		/* imprime el codigo de la grafica seleccionada en un textarea*/
		,n=CodeMirror(j[0],
		{
			value:"TES"
			,readOnly:e
			//,lineNumbers:!0
			,mode:m.codeMirrorType
		})
		,e||(g.delegate(".run","click"
		,function()
		{
			d=n.getValue()
			,o()
		})
		,o())
		,window.onerror=function(a,b,c)
		{
			return p(a,b,c)
			,console.log(a)
			,ONERROR&&$.isFunction(ONERROR)?ONERROR(a,b,c):!1
		}
		,COUNT++
		,this.setExample=function(a,b)
		{
			d=m.example({args:b,example:a,render:h})
			,n.setValue("TES")
			,n.refresh()
			,o()
		}
	}
		var 
		ONERROR=window.onerror
		,COUNT=0,TYPES={}
		,T_CONTROLS='<div class="controls"></div>'// boton para actualizar si esta el codigo impreso <button class="run btn large primary">.</button>
		,T_EDITOR='<div class="editor"></div>'
		,T_SOURCE='<div class="source"></div>'
		,T_RENDER='<div class="render"></div>'
		,T_ERRORS='<div class="errors"></div>'
		,T_IFRAME="<iframe></iframe>";
		TYPES.javascript=function(b)
		{
			this.onerror=b.onerror
		}
		,TYPES.javascript.prototype=
		{
			codeMirrorType:"javascript"
			,example:function(a)
			{
				var b=a.example
				,c=a.render
				,d=$(c).attr("id")
				,e=a.args?","+a.args.toString():"";
				return"("+b+')(document.getElementById("'+d+'")'+e+");"
			}
			,render:function(o){eval(o.example)}
				
		}
		,TYPES.html=function(b)
		{
			this.onerror=b.onerror
		}
		,TYPES.html.prototype=
		{
			codeMirrorType:"htmlmixed"
			,example:function(a)
			{
				return $.trim(a.example)
			}
			,render:function(a)
			{
				var b=a.example
				,c=a.render
				,d=$(T_IFRAME)
				,e=this
				,f
				,g;c.html(d)
				,f=d[0].contentWindow
				,g=f.document
				,g.open()
				,f.onerror=d.onerror=function()
				{
					e.onerror.apply(null,arguments)
				}
				,g.write(b),g.close()
			}
		}
		,typeof Flotr.Examples=="undefined"&&(Flotr.Examples={})
		,Flotr.Examples.Editor=Editor}()
		,function(){var a=Flotr.DOM,b=Flotr.EventAdapter
		,c=Flotr._
		,d="click"
		,e="example-profile"
		,f="examples"
		,g=function(a){if(c.isUndefined(Flotr.ExampleList))throw"Flotr.ExampleList not defined.";
		this.editMode="off"
		,this.list=Flotr.ExampleList
		,this.current=null
		,this.single=!1,this.init()}
		;g.prototype=c.extend({}
		,Flotr.Examples.prototype
		,{examples:function()
		{
			var e=document.getElementById(f)
			,g=a.node("<ul></ul>")
			,h;c.each(this.list.getType("profile")
			,function(e)
			{
				h=a.node("<li>"+e.name+"</li>")
				,a.insert(g,h)
				,b.observe(h,d,c.bind(function()
				{
					this.example(e)
				},this)
			)
		}
		,this)
		,a.insert(e,g)
	}
	,example:function(a)
	{
		this._renderSource(a)
		,this.profileStart(a)
		,setTimeout(c.bind(function()
		{
			this._renderGraph(a)
			,this.profileEnd()
		}
		,this),50)
	}
	,profileStart:function(a)
	{
		var b=document.getElementById(e);
		this._startTime=new Date,b.innerHTML='<div>Profile started for "'+a.name+'"...</div>'
	}
	,profileEnd:function(a)
	{

		var b=document.getElementById(e);
		profileTime=new Date-this._startTime
		,this._startTime=null
		,b.innerHTML+="<div>Profile complete: "+profileTime+"ms<div>"
	}
	})
	,Flotr.Profile=g
}()
$().ready(function () 
{

  Examples = new Flotr.Examples({
    node : document.getElementById('examples'),
    thumbPadding : 150
  });

  var
    examples  = $('#examples').find('.flotr-examples'),
    thumbs    = examples.find('.flotr-examples-thumbs'),
    offset    = 128,
    page      = $(window);

  $(document)
    .scroll(handleScrollSize);
  page
    .resize(handleResizeSize);

  function handleScrollSize () {
    if (examples.hasClass('flotr-examples-collapsed') && (examples.hasClass('flotr-examples-large') || examples.hasClass('flotr-examples-medium'))) {
      if (_.isNull(offset)) {
        offset = parseInt(thumbs.css('top'));
        if (_.isNaN(offset)) {
          offset = 0;
        }
      }
      var
        scrollTop = $(document).scrollTop(),
        top = Math.max(0, offset - scrollTop);
      thumbs.css({
        top : top,
        height : page.height() - 22 - top
      });
      Examples.options.thumbPadding = top + 22
    }
  }

  handleResizeSize();

  function handleResizeSize () {
    if (examples.hasClass('flotr-examples-collapsed') && (examples.hasClass('flotr-examples-large') || examples.hasClass('flotr-examples-medium'))) {
      handleScrollSize();
    } else {
      thumbs.css({
        height : 'auto',
        top : 'auto'
      });
    }
  }
  
  
});
</script>
</body>
</html>
