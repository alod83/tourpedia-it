var map;
function inizializza(){
	map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat: 42.0, lng: 12.0},
	  zoom: 6
	});
}

function Unique(inputArray){
    var temporaryArray = {};
	var outputArray = [];
    for (var i = 0; i < inputArray.length; i++){
		temporaryArray[inputArray[i]] = true;
	}
	for (var j in temporaryArray){
		outputArray.push(j);
	}
    return outputArray;
}
/*TOGLIE I SEGNALINI, CANCELLA L'ARRAY markers SE ESISTENTE E LO RICREA VUOTO PER UNA NUOVA RICERCA*/
function clearMarkers(markers) {
    // Clear all markers
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    // Recreate the Markers array
    delete markers;
    markers = new Array();
}
/*CREA I SEGNALINI SULLA MAPPA, STRUTTURE E ATTRAZIONI, IN BASE AL PARAMETRO PASSATO NEL CAMPO DI RICERCA*/
function createMarker(map, markers){
	var place = document.form_ricerca.ricerca_luogo.value;
	if (place.substr(place.length-1, 1) == ")"){
		place = place.substr(0, place.length-5);
	}
	$.getJSON("../api/query.php?category=accommodation&place="+place, function (data) {
		for (i=0; i<data.length; i++){
			if(((data[i].latitude != 0)&&(data[i].longitude != 0))&&((data[i].latitude != null)&&(data[i].longitude != null))){
				if(data[i].region == "Lombardia"){
					coordinate = {lat: data[i].longitude, lng: data[i].latitude};
				}else{
					coordinate = {lat: data[i].latitude, lng: data[i].longitude};
				}
				nome = data[i].name;
				var marker = new google.maps.Marker({
					position: coordinate,
					map: map,
					icon: 'images/bed_b.png',
					title: data[i].name,
					content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+", "+data[i].city+" ("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email
				});
				var infowindow = new google.maps.InfoWindow({ 
					position: coordinate,
					content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
					size: new google.maps.Size(50,50)
				});
				google.maps.event.addListener(marker, 'click', function(event) {
					infowindow.setContent(this.content);
					infowindow.open(map, this);
				});
				markers.push(marker);
			}
		}
		$("#ns").html("("+data.length+")");
	});
	$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
		for (i=0; i<data.length; i++){
			if(((data[i].latitude != 0)&&(data[i].longitude != 0))&&((data[i].latitude != null)&&(data[i].longitude != null))){
				coordinate = {lat: data[i].latitude, lng: data[i].longitude};
				nome = data[i].name;
				var marker = new google.maps.Marker({
					position: coordinate,
					map: map,
					icon: 'images/attraction.png',
					title: data[i].name,
					content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+", "+data[i].city+" ("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email
				});
				var infowindow = new google.maps.InfoWindow({ 
					position: coordinate,
					content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
					size: new google.maps.Size(50,50)
				});
				google.maps.event.addListener(marker, 'click', function(event) {
					infowindow.setContent(this.content);
					infowindow.open(map, this);
				});
				markers.push(marker);
			}
		}
		$("#na").html("("+data.length+")");
	});
}
/*AL CARICAMENTO DELLA PAGINA, CREA LA MAPPA, ,CREA L'EVENTO CLICK DEL TASTO CERCA,E QUELLI RELATIVI AL TASTO X*/
$(document).ready(function() {
	/*NASCONDO IL TASTO X DELLA RICERCA TESTUALE*/
	$("#x").hide();
	/*INIZIALIZZO I CONTATORI PER L'APERTURA E LA CHIUSURA DEI BOX DI RICERCA*/
	var contatori = [];
	for (var i=0; i<$(".expand").length; i++){
		contatori[i]=0;
	}
	/*INIZIALIZZO LA MAPPA*/
	var markers = new Array();
	/*??????*/
	$("#ricerca_luogo").keypress(function(e) {
		if (e.keyCode == 13){
			e.preventDefault();
			clearMarkers(markers);
			createMarker(map, markers);
		}
	});
	/*CREO L'EVENTO DELLA RICERCA TESTUALE*/
	$("#cerca").click(function(event) {
		event.preventDefault();
		clearMarkers(markers);
		createMarker(map, markers);
	});
	// if text input field value is not empty show the "X" button
	$("#ricerca_luogo").keyup(
		function() 
		{
			$("#x").fadeIn();
			if ($.trim($("#ricerca_luogo").val()) == "") 
			{
		 	   $("#x").fadeOut();
			}
		}
	);
	/*AL CLICK DELLA X, CANCELLO IL CONTENUTO DELLA RICERCA TESTUALE E NASCONDO IL TASTO X*/
	// on click of "X", delete input field value and hide "X"
	$("#x").click(function(event) {
			event.preventDefault();
			$("#ricerca_luogo").val("");
			$(this).hide();
		}
	);
	/* al click del TASTO DI ESPANSIONE, APRO IL CORRISPONDENTE BOX DI RICERCA*/
	$(".expand").click(function(){
		if(contatori[this.value]==0){
			$(".expand:eq("+this.value+")").attr("src","images/up.png");
			$(".campi_ricerca:eq("+this.value+")").addClass("campi_ricerca open");
			contatori[this.value]=1;
		}else if(contatori[this.value]==1){
			$(".expand:eq("+this.value+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+this.value+")").removeClass("open");
			contatori[this.value]=0;
		}
	});
	/*$( function() {
		$.ajaxSetup({
			async: false
		});
		var Tags=[];
		$.getJSON("../api/query.php?category=accommodation", function (data) {
			for(var i=0; i<data.length; i++){
				Tags.push(data[i].region);
				Tags.push(data[i].city);
			}
		});
		var availableTags = Unique(Tags);
		$( "#ricerca_luogo" ).autocomplete({
			source: function(req, responseFn) {
				var re = $.ui.autocomplete.escapeRegex(req.term);
				var matcher = new RegExp( "^" + re, "i" );
				var a = $.grep( availableTags, function(item,index){
					return matcher.test(item);
				});
				responseFn( a );
			}
		});
	});*/
});