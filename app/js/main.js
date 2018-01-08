//Creo la mappa e la inizializzo
var map;
var ResultsS="";
var ResultsA="";
/*INIZIALIZZO LA MAPPA*/
var markersS = new Array();
var markersA = new Array();
var contatori = [];
var markerClusterS = null;
var markerClusterA = null;
function inizializza(){
	map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat: 42.0, lng: 12.0},
	  zoom: 6,
	  mapTypeControl: false,
	  fullscreenControl: false
	});
	/*SE ATTIVO STREETWIEW LO RENDO VISIBILE*/
	var thePanorama = map.getStreetView();
	google.maps.event.addListener(thePanorama, 'visible_changed', function() {
		if (thePanorama.getVisible()) {
			$("#main").css('z-index','4');
		} else {
			$("#main").css('z-index','0');
		}
	});
	markerClusterS = new MarkerClusterer(map);
	markerClusterA = new MarkerClusterer(map);
	$( window ).resize(function() {
		console.log($(window).height());
		$( ".open" ).css("height", ($(window).height()-184)+"px");
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
//Al click del tasto X della scheda delle STRUTTURE, la scheda si chiude e ricompare la lista di strutture ricercata
function ChiudiS(){
	$("#ricercaS").html(ResultsS);
	for(var i = 0; i<markersS.length; i++){
		if(markersS[i].icon == 'images/structMar.png'){
			markersS[i].setIcon('images/bed_b.png');
		}
	}
	Hover();
	Click();
	var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };
	listObj = new List('ricercaS', options);
};
//Al click del tasto X della scheda delle ATTRAZIONI, la scheda si chiude e ricompare la lista di attrazioni ricercata
function ChiudiA(){
	$("#ricercaA").html(ResultsA);
	for(var i = 0; i<markersA.length; i++){
		if(markersA[i].icon == 'images/attrMar.png'){
			markersA[i].setIcon('images/attraction.png');
		}
	}
	Hover();
	Click();
	var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };
	listObj2 = new List('ricercaA', options);
};
//inserisco la lista dei risultati
function insertResult(){
	var place = document.form_ricerca.ricerca_luogo.value;
	var url= "../api/query.php?category=accommodation&place=";
	if (place=="Trentino-alto adige"){
		url+="Trentino";
	}else{
		url+=place;
	}
	ResultsS = "<ul class='list'>";
	$.getJSON(url, function (data) {
		for (i=0; i<data.length; i++){
			if(data[i].latitude && data[i].longitude){
				var stringa="";
				if(data[i].name){
					stringa+="<p>"+data[i].name+"</p>";
				}
				if(data[i].address){
					stringa+="<p>"+data[i].address+"</p>";
				}
				ResultsS += "<li class='result' id="+data[i].name.replace(/ /g,"_")+"><div class='i'><div class='info'><p>"+stringa+"</p></div><div class='anteprima'><img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+data[i].latitude+","+data[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div></div></li>";
			}
		}
	});
	ResultsS += "</ul><ul class='pagination'></ul>";
	$("#ricercaS").html(ResultsS);
	ResultsA = "<ul class='list'>";
	$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
		for (i=0; i<data.length; i++){
			if(data[i].latitude && data[i].longitude){
				var stringa="";
				if(data[i].name){
					stringa+="<p>"+data[i].name+"</p>";
				}
				if(data[i].description){
					stringa+="<p>"+data[i].description+"</p>";
				}
				ResultsA += "<li class='result' id="+data[i].name.replace(/ /g,"_")+"><div class='i'><div class='info'><p>"+stringa+"</p></div><div class='anteprima'><img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+data[i].latitude+","+data[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div></div></li>";			
			}
		}
	});
	ResultsA += "</ul><ul class='pagination'></ul>";
	$("#ricercaA").html(ResultsA);
}
/*FUNZIONE HOVER SULLA LISTA*/
function Hover(){
	$(".result").hover(
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title.replace(/ /g,"_")){
					if(markersA[i].icon == 'images/attraction.png'){
						markersA[i].setIcon('images/attrMar.png');
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title.replace(/ /g,"_")){
					if(markersS[j].icon == 'images/bed_b.png'){
						markersS[j].setIcon('images/structMar.png');
					}
				}
			}
		},
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title.replace(/ /g,"_")){
					if(markersA[i].icon == 'images/attrMar.png'){
						markersA[i].setIcon('images/attraction.png');
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title.replace(/ /g,"_")){
					if(markersS[j].icon == 'images/structMar.png'){
						markersS[j].setIcon('images/bed_b.png');
					}
				}
			}
		}
	)
}
/*FUNZIONE CLICK SULLA LISTA*/
function Click(){
	$(".result").click(
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title.replace(/ /g,"_")){
					if(markersA[i].icon == 'images/attrMar.png'){
						$("#ricercaA").html(markersA[i].content);
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title.replace(/ /g,"_")){
					if(markersS[j].icon == 'images/structMar.png'){
						$("#ricercaS").html(markersS[j].content);
					}
				}
			}
		}
	)
}
/*TOGLIE I SEGNALINI, CANCELLA L'ARRAY markers SE ESISTENTE E LO RICREA VUOTO PER UNA NUOVA RICERCA*/
function clearMarkers() {
    // Clear all markers
    for (var i = 0; i < markersS.length; i++) {
        markersS[i].setMap(null);
    }
	for (var j = 0; j < markersA.length; j++) {
		markersA[j].setMap(null);
	}		
	markersS.length = 0;
	markersA.length = 0;
    // Recreate the Markers array
	markerClusterS.clearMarkers();
	markerClusterA.clearMarkers();
}
/*CREA I SEGNALINI SULLA MAPPA, STRUTTURE E ATTRAZIONI, IN BASE AL PARAMETRO PASSATO NEL CAMPO DI RICERCA*/
function createMarker(map){
	var place = document.form_ricerca.ricerca_luogo.value;
	if (place){
		var url= "../api/query.php?category=accommodation&place=";
		if (place=="Trentino-alto adige"){
			url+="Trentino";
		}else{
			url+=place;
		}
		$.getJSON(url, function (data) {
			if(data.length != 0){
				/*var correctData=0;*/
				for (i=0; i<data.length; i++){
					if(data[i].latitude && data[i].longitude){
						if(place==data[i].region){
							map.setZoom(8);
						}
						if(place==data[i].city){
							map.setZoom(10);
						}
						var string="<div id='schedaS'><form><input class='chiudi' type='image' src='images/x.png' onclick='ChiudiS()'></form>";
						coordinate = {lat: data[i].latitude, lng: data[i].longitude};
						map.setCenter({ lat : data[0].latitude , lng : data[0].longitude });
						string+="<div class='foto'><img src='https://maps.googleapis.com/maps/api/streetview?size=300x200&location="+data[i].latitude+","+data[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div>"
						if(data[i].name){
							string+="<p>"+data[i].name+"</p>";
						}
						if(data[i].stars){
							for(var y=0; y<data[i].stars; y++){
								string+="<img class='star' src='images/star.svg'>";
							}
							string+="<br/>";
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
						if(data[i].telephone){
							string+="<img class='icons' src='images/telephone.svg' alt='telephone'><p class='infoscheda'> Tel: "+data[i].telephone+"</p></br>";
						}
						if(data[i].email){
							string+="<img class='icons' src='images/mail.svg' alt='email'><p class='infoscheda'> Email: <a href='mailto:"+data[i].email+"'>"+data[i].email+"</a></p></br>";
						}
						if(data[i]['web site']){
							string+="<img class='icons' src='images/internet.svg' alt='internet'><p class='infoscheda'> <a href='http://"+data[i]['web site']+"'>"+data[i]['web site']+"</a></p></br>";
						}
						string+="</div>"
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
							$('#navigation').css('left','0');
							$('#arrow').attr("src","images/left arrow.svg");
							contatori[contatori.length-1]=1;
						});
						markersS.push(marker);
					}
				}
				if (markersS.length != 0){
					$("#ns").html("("+markersS.length+")");
				}else{
					$("#ns").html("(Nessun risultato trovato)");
				}
			}else{
				$("#ns").html("(Nessun risultato trovato)");
			}
		});
		$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
			if(data.length != 0){
				for (i=0; i<data.length; i++){
					if(data[i].latitude && data[i].longitude){
						coordinate = {lat: data[i].latitude, lng: data[i].longitude};
						if(place==data[i].region){
							map.setZoom(8);
						}
						if(place==data[i].city){
							map.setZoom(10);
						}
						map.setCenter({ lat : data[0].latitude , lng : data[0].longitude });
						var string="<div id='schedaA'><form><input class='chiudi' type='image' src='images/x.png' onclick='ChiudiA()'></form>";
						string+="<img class='foto' src='https://maps.googleapis.com/maps/api/streetview?size=300x200&location="+data[i].latitude+","+data[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'><br/>"
						if(data[i].name){
							string+="<p>"+data[i].name+"</p>";
						}
						if(data[i].category){
							string+="<p>"+data[i].category+"</p>";
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
						if(data[i].telephone){
							string+="<img class='icons' src='images/telephone1.svg' alt='telephone'><p class='infoscheda'>Tel: "+data[i].telephone+"</p></br>";
						}
						if(data[i].email){
							string+="<img class='icons' src='images/mail1.svg' alt='email'><p class='infoscheda'>Email: <a href='mailto:"+data[i].email+"'>"+data[i].email+"</a></p></br>";
						}
						if(data[i].url){
							string+="<img class='icons' src='images/internet1.svg' alt='internet'><p class='infoscheda'> <a href='http://"+data[i].url+"'>"+data[i].url+"</a></p></br>";
						}
						string+="</div>"
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
							$('#navigation').css('left','0');
							$('#arrow').attr("src","images/left arrow.svg");
							contatori[contatori.length-1]=1;
						});
						markersA.push(marker);
					}
				}
				if(markersA.length != 0){
					$("#na").html("("+markersA.length+")");
				}else{
					$("#na").html("(Nessun risultato trovato)");
				}
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
	for (var i=0; i<$(".expand").length; i++){
		contatori[i]=0;
	}
	contatori.push(0);
	/*RICERCA SE PREMO INVIO*/
	$("#ricerca_luogo").keypress(function(e) {
		if (e.keyCode == 13){
			e.preventDefault();
			insertResult();
			clearMarkers();
			createMarker(map);
			markerClusterS = new MarkerClusterer(map, markersS,
				{imagePath: 'images/m'}
			);
			markerClusterA = new MarkerClusterer(map, markersA,
				{imagePath: 'images/g'}
			);
			var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };
			 
			var listObj = new List('ricercaS', options);
			var listObj2 = new List('ricercaA', options);
			$('#ui-id-1').hide();
			$('#navigation').css('left','0');
			contatori[contatori.length-1]=1;
			$('#arrow').attr("src","images/left arrow.svg");
			if($(".list:eq("+0+")").html() !== ""){
				$(".expand:eq("+0+")").attr("src","images/up.png");
				$(".campi_ricerca:eq("+0+")").addClass("campi_ricerca open");
				$(".campi_ricerca:eq("+0+")").css("height", ($(window).height()-184)+"px");
				contatori[0]=1;
				$(".expand:eq("+1+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+1+")").removeClass("open");
				$(".campi_ricerca:eq("+1+")").css("height", "0");
				contatori[1]=0;
			}else if($(".list:eq("+1+")").html() !== ""){
				$(".expand:eq("+1+")").attr("src","images/up.png");
				$(".campi_ricerca:eq("+1+")").addClass("campi_ricerca open");
				$(".campi_ricerca:eq("+1+")").css("height", ($(window).height()-184)+"px");
				contatori[1]=1;
				$(".expand:eq("+0+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+0+")").removeClass("open");
				$(".campi_ricerca:eq("+0+")").css("height", "0");
				contatori[0]=0;
			}else{
				$(".expand:eq("+0+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+0+")").removeClass("open");
				$(".campi_ricerca:eq("+0+")").css("height", "0");
				contatori[0]=0;
				$(".expand:eq("+1+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+1+")").removeClass("open");
				$(".campi_ricerca:eq("+1+")").css("height", "0");
				contatori[1]=0;
			}
			console.log(markersA,markersS);
			Hover();
			Click();
		}
	});
	/*CREO L'EVENTO DELLA RICERCA TESTUALE*/
	$("#cerca").click(function(event) {
		event.preventDefault();
		insertResult();
		clearMarkers();
		createMarker(map);
		markerClusterS = new MarkerClusterer(map, markersS,
			{imagePath: 'images/m'}
		);
		markerClusterA = new MarkerClusterer(map, markersA,
			{imagePath: 'images/g'}
		);
		var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };

		var listObj = new List('ricercaS', options);
		var listObj2 = new List('ricercaA', options);
		$('#navigation').css('left','0');
		contatori[contatori.length-1]=1;
		$('#arrow').attr("src","images/left arrow.svg");
		if($(".list:eq("+0+")").html() !== ""){
			$(".expand:eq("+0+")").attr("src","images/up.png");
			$(".campi_ricerca:eq("+0+")").addClass("campi_ricerca open");
			$(".campi_ricerca:eq("+0+")").css("height", ($(window).height()-184)+"px");
			contatori[0]=1;
			$(".expand:eq("+1+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+1+")").removeClass("open");
			$(".campi_ricerca:eq("+1+")").css("height", "0");
			contatori[1]=0;
		}else if($(".list:eq("+1+")").html() !== ""){
			$(".expand:eq("+1+")").attr("src","images/up.png");
			$(".campi_ricerca:eq("+1+")").addClass("campi_ricerca open");
			$(".campi_ricerca:eq("+1+")").css("height", ($(window).height()-184)+"px");
			contatori[1]=1;
			$(".expand:eq("+0+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+0+")").removeClass("open");
			$(".campi_ricerca:eq("+0+")").css("height", "0");
			contatori[0]=0;
		}else{
			$(".expand:eq("+0+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+0+")").removeClass("open");
			$(".campi_ricerca:eq("+0+")").css("height", "0");
			contatori[0]=0;
			$(".expand:eq("+1+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+1+")").removeClass("open");
			$(".campi_ricerca:eq("+1+")").css("height", "0");
			contatori[1]=0;
		}
		Hover();
		Click();
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
			clearMarkers();
		}
	);
	/*AL CLICK DEL TASTO INDICATO DALLA FRECCIA, APRO E CHIUDO IL DIV DI NAVIGAZIONE*/
	$("#tasto").click(function(){
		if(contatori[contatori.length-1]==0){
			console.log("miao");
			$('#navigation').css('left','0');
			$('#arrow').attr("src","images/left arrow.svg");
			contatori[contatori.length-1]=1;
		}else if(contatori[contatori.length-1]==1){
			console.log("ciao");
			$('#navigation').css('left','-30%');
			$('#arrow').attr("src","images/right arrow.svg");
			contatori[contatori.length-1]=0;
		}
	});
	/* AL CLICK DEL TASTO DI ESPANSIONE, APRO IL CORRISPONDENTE BOX DI RICERCA*/
	$(".expand").click(function(){
		if(contatori[this.value]==0){
			if($(".list:eq("+this.value+")").html() !== ""){
				if(this.value==0){
					if($(".list:eq("+1+")").html() !== ""){
						$(".expand:eq("+1+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+1+")").removeClass("open");
						$(".campi_ricerca:eq("+1+")").css("height", "0");
						contatori[1]=0;
						$(".expand:eq("+this.value+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+this.value+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+this.value+")").css("height", ($(window).height()-184)+"px");
						contatori[this.value]=1;
					}
				}else if(this.value==1){
					if($(".list:eq("+0+")").html() !== ""){
						$(".expand:eq("+0+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+0+")").removeClass("open");
						$(".campi_ricerca:eq("+0+")").css("height", "0");
						contatori[0]=0;
						$(".expand:eq("+this.value+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+this.value+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+this.value+")").css("height", ($(window).height()-184)+"px");
						contatori[this.value]=1;
					}
				}
			}else{
				$(".expand:eq("+this.value+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+this.value+")").removeClass("open");
				$(".campi_ricerca:eq("+this.value+")").css("height", "0");
				contatori[this.value]=0;
			}
		}else if(contatori[this.value]==1){
			if($(".list:eq("+this.value+")").html() !== ""){
				if(this.value==0){
					if($(".list:eq("+1+")").html() !== ""){
						$(".expand:eq("+1+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+1+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+1+")").css("height", ($(window).height()-184)+"px");
						contatori[1]=1;
						$(".expand:eq("+this.value+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+this.value+")").removeClass("open");
						$(".campi_ricerca:eq("+this.value+")").css("height", "0");
						contatori[this.value]=0;
					}else{
						$(".expand:eq("+1+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+1+")").removeClass("open");
						$(".campi_ricerca:eq("+1+")").css("height", "0");
						contatori[1]=0;
					}
				}else if(this.value==1){
					if($(".list:eq("+0+")").html() !== ""){
						$(".expand:eq("+0+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+0+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+0+")").css("height", ($(window).height()-184)+"px");
						contatori[0]=1;
						$(".expand:eq("+this.value+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+this.value+")").removeClass("open");
						$(".campi_ricerca:eq("+this.value+")").css("height", "0");
						contatori[this.value]=0;
					}else{
						$(".expand:eq("+0+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+0+")").removeClass("open");
						$(".campi_ricerca:eq("+0+")").css("height", "0");
						contatori[0]=0;
					}
				}
			}
		}
	});
	/*SUGGERIMENTI RICERCA TESTUALE*/
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