<?php echo $api;?>
<script type="text/javascript">
	var map;
	var markerarray = [];
	var marker;
	var marcador=null;
	var perfi=0,categ=0,gir=0,nive=0, proce=0;
	var ciudad;
	var popup;
	var listenerzoom;
	var img_primer_nivel;
	var conteo;
	var ultimas;
	var note;
	var slug;
	var vista_estado=false;
	var geocoder;
	var lat=16;
	var lng=-93;
	var lgm="Chiapas";
	
$(document).ready(function(){
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({ 'address': "<?php echo $lugar;?>" }, function (results, status) 
	{
		if (status == google.maps.GeocoderStatus.OK) 
		{
			lgm=results[0].address_components[0].long_name;
			lat=results[0].geometry.location.lat();
			lng=results[0].geometry.location.lng();
			
			crear_mapa(<?php echo $zoom;?>,'map');
			cargar_mapa();
		} 
		else 
		{
			crear_mapa(<?php echo $zoom;?>,'map');
			console.log("Geocoding failed for " + address + ": " + status);
		}
	});
});

function crear_mapa(cerca,elemento)
{
	var latlng = new google.maps.LatLng(lat, lng);
	var options = 
	{
		zoom: cerca,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		scrollwheel:false,
		panControl: true, 
		navigationControl: true
	};
	map = new google.maps.Map(document.getElementById(elemento),options);
}	
	

function cargar_mapa()
{
var lista = [<?php echo $array;?>];

	clearOverlays();
    for(i=0,j=0;j<lista.length/4;j++,i=i+6)
	{
	 	var latlng = new google.maps.LatLng(lista[i+1],lista[i+2]);
        var marker = new google.maps.Marker(
		{
           	  position: latlng
            , map: map
            , title: lista[i]
			, info: '<div class="contentMap">\
    					<div class="contentTxt">\
    					<div class="title">'+lista[i]+'</div>\
						<table><tr>\
						<td valing="top"><div class="contentImg"><img src="'+lista[i+4]+'" width="60" height="60" /></div></td>\
    					<td valing="top"><div class="text">'+lista[i+3]+'</div></td></tr>\
    					</div>\
						</div>'
	        , draggable: false			
			, animation: google.maps.Animation.DROP						
            , icon: lista[i+5]
        });
		markerarray.push(marker);
		marker.setMap(map);
		google.maps.event.addListener(marker, 'click', function(event) 
		{
			var newpos = new google.maps.LatLng(this.getPosition().lat()*1.05,this.getPosition().lng());
			map.setCenter(newpos);
			if(!popup)
			{
				popup= new google.maps.InfoWindow();
			}
			popup.setContent(this['info']);
			popup.open(map, this);
		});
	}
}


function clearOverlays() 
{
	if (markerarray) 
	{
		for (i in markerarray) 
		{
			markerarray[i].setMap(null);
		}
	}
}

	

</script>
<style>
.google-map-canvas,
.google-map-canvas * { .box-sizing(content-box); }

/* Optional responsive image override */
img { max-width: none; }
</style>
<div id="map" style="border:1px #333333; height:100%; width:100%; display:block; padding-top:-20px"></div>
