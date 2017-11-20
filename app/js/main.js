//Creo la mappa e la inizializzo
var map;
function inizializza(){
	map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat: 42.0, lng: 12.0},
	  zoom: 6,
	  mapTypeControl: false,
	  fullscreenControl: false
	});
}
//Prende un array in input, e restuisce come output un array con gli elementi del primo, senza doppioni
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
//inserisco la lista dei risultati
function insertResult(){
	var place = document.form_ricerca.ricerca_luogo.value;
	var ResultsS="<ul>";
	var ResultsA="<ul>";
	var url= "../api/query.php?category=accommodation&place=";
	if (place=="Trentino-alto adige"){
		url+="Trentino";
	}else{
		url+=place;
	}
	$.getJSON(url, function (data) {
		for (i=0; i<data.length; i++){
			if(data[i].latitude && data[i].longitude){
				ResultsS += "<li>"+data[i].name+"</li>";
			}
		}
	});
	ResultsS += "</ul>";
	$("#ricercaS").html(ResultsS);
	$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
		for (i=0; i<data.length; i++){
			if(data[i].latitude && data[i].longitude){
				ResultsA += "<li>"+data[i].name+"</li>";
			}
		}
	});
	ResultsA += "</ul>";
	$("#ricercaA").html(ResultsA);
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
	if (place){
		map.setZoom(8);
		var url= "../api/query.php?category=accommodation&place=";
		if (place=="Trentino-alto adige"){
			url+="Trentino";
		}else{
			url+=place;
		}
		$.getJSON(url, function (data) {
			var correctData=0;
			for (i=0; i<data.length; i++){
				if(data[i].latitude && data[i].longitude){
					correctData+=1;
					if(data[i].region == "Lombardia"){
						coordinate = {lat: data[i].longitude, lng: data[i].latitude};
					}else{
						coordinate = {lat: data[i].latitude, lng: data[i].longitude};
					}
					map.setCenter({ lat : data[0].latitude , lng : data[0].longitude });
					var string="";
					if(data[i].name){
						string+="<p>"+data[i].name+"</p>";
					}
					if(data[i].description){
						string+="<p>"+data[i].description+"</p>";
					}
					if(data[i].address){
						string+=data[i].address+", ";
					}
					if(data[i].city){
						string+=data[i].city;
					}
					if(data[i].province){
						string+="("+data[i].province+")<br/>";
					}
					if(data[i].stars){
						for(var y=0; y<data[i].stars; y++){
							string+="<img class='star' src='images/star.svg'>";
						}
						string+="<br/>";
					}
					if(data[i].telephone){
						string+="<p><img class='icons' src='images/telephone.svg' alt='telephone'> Tel: "+data[i].telephone+"</p>";
					}
					if(data[i].email){
						string+="<p><img class='icons' src='images/mail.svg' alt='email'> Email: "+data[i].email+"</p>";
					}
					if(data[i]['web site']){
						string+="<p><img class='icons' src='images/internet.svg' alt='internet'> "+data[i]['web site']+"</p>";
					}
					var marker = new google.maps.Marker({
						position: coordinate,
						map: map,
						icon: 'images/bed_b.png',
						title: data[i].name,
						content: string
					});
					/*var infowindow = new google.maps.InfoWindow({ 
						position: coordinate,
						content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
						size: new google.maps.Size(50,50)
					});*/
					google.maps.event.addListener(marker, 'click', function(event) {
						/*infowindow.setContent(this.content);
						infowindow.open(map, this);*/
						$("#ricercaS").html(this.content);
					});
					markers.push(marker);
				}
			}
			if(correctData != 0){
				$("#ns").html("("+correctData+")");
			}else{
				$("#ns").html("(Nessun risultato trovato)");
			}
		});
		$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
			var correctData=0;
			for (i=0; i<data.length; i++){
				if(data[i].latitude && data[i].longitude){
					coordinate = {lat: data[i].latitude, lng: data[i].longitude};
					correctData+=1;
					var string="";
					if(data[i].name){
						string+=data[i].name+"<br/>";
					}
					if(data[i].description){
						string+=data[i].description+"<br/>";
					}
					if(data[i].address){
						string+=data[i].address+", ";
					}
					if(data[i].city){
						string+=data[i].city;
					}
					if(data[i].province){
						string+="("+data[i].province+")<br/>";
					}
					if(data[i].telephone){
						string+="Tel: "+data[i].telephone+"<br/>";
					}
					if(data[i].email){
						string+="email: "+data[i].email;
					}
					var marker = new google.maps.Marker({
						position: coordinate,
						map: map,
						icon: 'images/attraction.png',
						title: data[i].name,
						content: string
					});
					/*var infowindow = new google.maps.InfoWindow({ 
						position: coordinate,
						content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
						size: new google.maps.Size(50,50)
					});*/
					google.maps.event.addListener(marker, 'click', function(event) {
						//infowindow.setContent(this.content);
						//infowindow.open(map, this);
						$("#ricercaA").html(this.content);
					});
					markers.push(marker);
				}
			}
			if(correctData != 0){
				$("#na").html("("+correctData+")");
			}else{
				$("#na").html("(Nessun risultato trovato)");
			}
		});
	}
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
	contatori.push(0);
	/*INIZIALIZZO LA MAPPA*/
	var markers = new Array();
	/*??????*/
	$("#ricerca_luogo").keypress(function(e) {
		if (e.keyCode == 13){
			e.preventDefault();
			clearMarkers(markers);
			createMarker(map, markers);
			$('#navigation').css('left','0');
			contatori[contatori.length-1]=1;
			$('#arrow').attr("src","images/left arrow.svg");
			insertResult();
		}
	});
	/*CREO L'EVENTO DELLA RICERCA TESTUALE*/
	$("#cerca").click(function(event) {
		event.preventDefault();
		clearMarkers(markers);
		createMarker(map, markers);
		$('#navigation').css('left','0');
		contatori[contatori.length-1]=1;
		$('#arrow').attr("src","images/left arrow.svg");
		insertResult();
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
			clearMarkers(markers);
			$("#ns").html("");
			$("#na").html("");
		}
	);
	$("#tasto").click(function(){
		if(contatori[contatori.length-1]==0){
			$('#navigation').css('left','0');
			$('#arrow').attr("src","images/left arrow.svg");
			contatori[contatori.length-1]=1;
		}else if(contatori[contatori.length-1]==1){
			$('#navigation').css('left','-30%');
			$('#arrow').attr("src","images/right arrow.svg");
			contatori[contatori.length-1]=0;
		}
	});
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
	$( function() {
		$.ajaxSetup({
			async: false
		});
		var Tags=[];
		$.getJSON("../api/query.php?category=accommodation", function (data) {
			for(var i=0; i<data.length; i++){
				if(data[i].latitude && data[i].longitude){
					Tags.push(data[i].region.substr(0,1).toUpperCase()+data[i].region.substr(1).toLowerCase());
					Tags.push(data[i].city.substr(0,1).toUpperCase()+data[i].city.substr(1).toLowerCase());
				}
			}
		});
		$.getJSON("../api/query.php?category=attraction", function (data) {
			for(var i=0; i<data.length; i++){
				if(data[i].latitude && data[i].longitude){
					Tags.push(data[i].region.substr(0,1).toUpperCase()+data[i].region.substr(1).toLowerCase());
					Tags.push(data[i].city.substr(0,1).toUpperCase()+data[i].city.substr(1).toLowerCase());
				}
			}
		});
		var availableTags = Unique(Tags);
		$( "#ricerca_luogo" ).autocomplete({
			minLength: 2,
			source: function(req, responseFn) {
				var re = $.ui.autocomplete.escapeRegex(req.term);
				var matcher = new RegExp( "^" + re, "i" );
				var a = $.grep( availableTags, function(item,index){
					return matcher.test(item);
				});
				responseFn( a );
			}
		});
	});
});